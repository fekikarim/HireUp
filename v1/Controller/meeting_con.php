<?php

require_once __DIR__ . '/../config.php';
require __DIR__ . '/vendor/autoload.php';

/**
 * PHP code below is used to generate a JaaS JWT.
 * You can copy the code below in your implementation.
 */
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Core\JWK;
use Jose\Component\Signature\Algorithm\RS256;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\KeyManagement\JWKFactory;
use Jose\Component\Signature\Serializer\CompactSerializer;



class MeetingCon {

    private $tab_name;

    public function __construct($tab_name)
    {
        $this->tab_name = $tab_name;
    }

    public function generateId($id_length)
    {
        $numbers = '0123456789';
        $numbers_length = strlen($numbers);
        $random_id = '';

        // Generate random ID
        for ($i = 0; $i < $id_length; $i++) {
            $random_id .= $numbers[rand(0, $numbers_length - 1)];
        }

        return (string) $random_id; // Ensure the return value is a string
    }

    public function meetingExists($id, $db)
    {
        $sql = "SELECT COUNT(*) as count FROM $this->tab_name WHERE id = :id";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function generateMeetingId($id_length)
    {
        $db = config::getConnexion();

        do {
            $current_id = $this->generateId($id_length);
        } while ($this->meetingExists($current_id, $db));

        return $current_id;
    }

    public function getMeeting($id)
    {
        $sql = "SELECT * FROM $this->tab_name WHERE id = :id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindParam(':id', $id);
            $query->execute();
            $meeting = $query->fetch(PDO::FETCH_ASSOC);
            return $meeting;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function listMeetings()
    {
        $sql = "SELECT * FROM $this->tab_name";
        $db = config::getConnexion();

        try {
            $list = $db->query($sql);
            return $list->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function addMeeting($meeting)
    {
        $sql = "INSERT INTO $this->tab_name(id, room_name, creation_date, meeting_desc, meeting_at, meeting_job_id) VALUES (:id, :room_name, :creation_date, :meeting_desc, :meeting_at, :meetingJobId)";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $meeting->getId(),
                'room_name' => $meeting->getRoomName(),
                'creation_date' => $meeting->getCreationDate(),
                'meeting_desc' => $meeting->getmeetingDesc(),
                'meeting_at' => $meeting->getmeetingAt(),
                'meetingJobId' => $meeting->getmeetingJobId()
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function updateMeeting($meeting, $id)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE $this->tab_name SET room_name = :room_name, creation_date = :creation_date, meeting_desc = :meeting_desc, meeting_at = :meeting_at, meeting_job_id = :meetingJobId WHERE id = :id");
            $query->execute([
                'id' => $id,
                'room_name' => $meeting->getRoomName(),
                'creation_date' => $meeting->getCreationDate(),
                'meeting_desc' => $meeting->getmeetingDesc(),
                'meeting_at' => $meeting->getmeetingAt(),
                'meetingJobId' => $meeting->getmeetingJobId()
            ]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function deleteMeeting($id)
    {
        $sql = "DELETE FROM $this->tab_name WHERE id = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);

        try {
            $req->execute();
            return true;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
            return false;
        }
    }

    public function getMeetingParticipants($meeting_id){
        $sql = "SELECT * FROM meeting_participants WHERE meeting_id = :meeting_id";
        $db = config::getConnexion();
    
        try {
            $query = $db->prepare($sql);
            $query->bindParam(':meeting_id', $meeting_id);
            $query->execute();
            $participants = $query->fetchAll(PDO::FETCH_ASSOC); // Use fetchAll() instead of fetch()
            return $participants;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function getProfileMeetings($profile_id){
        $sql = "SELECT * FROM meeting_participants WHERE profile_id = :profile_id";
        $db = config::getConnexion();
    
        try {
            $query = $db->prepare($sql);
            $query->bindParam(':profile_id', $profile_id);
            $query->execute();
            $participants = $query->fetchAll(PDO::FETCH_ASSOC); // Use fetchAll() instead of fetch()
            return $participants;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function getMeetingIdByJobIdAndProfileId($meeting_job_id, $profile_id)
    {
        $sql = "SELECT mp.meeting_id 
                FROM meeting_participants mp
                JOIN meetings m ON mp.meeting_id = m.id
                WHERE m.meeting_job_id = :meeting_job_id
                AND mp.profile_id = :profile_id";
        
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindParam(':meeting_job_id', $meeting_job_id);
            $query->bindParam(':profile_id', $profile_id);
            $query->execute();
            $meeting_id = $query->fetch(PDO::FETCH_COLUMN);
            return $meeting_id;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function generateRandomWord($length=10) {
        // Character set to choose from, including special characters
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    
        // Get the length of the character set
        $charLength = strlen($characters);
    
        // Initialize the random word
        $randomWord = '';
    
        // Generate random word
        for ($i = 0; $i < $length; $i++) {
            // Get a random index within the character set
            $randomIndex = rand(0, $charLength - 1);
            
            // Append the randomly chosen character to the random word
            $randomWord .= $characters[$randomIndex];
        }
    
        return $randomWord;
    }

    public function generateJWT($mail, $name, $moderator, $userid=null, $room, $avatar="", $exp_delay_sec=7200, $nbf_delay_sec=10, $recording=false, $livestreaming=false, $transcription=false, $outbound=false, $api_key="vpaas-magic-cookie-df0c5b7739d54b0c85669b4cae1b3853/e23cd7", $app_id="vpaas-magic-cookie-df0c5b7739d54b0c85669b4cae1b3853") {

        /**
         * Change the variables below.
         */
        /*$API_KEY="vpaas-magic-cookie-df0c5b7739d54b0c85669b4cae1b3853/e23cd7";
        $APP_ID="vpaas-magic-cookie-df0c5b7739d54b0c85669b4cae1b3853"; // Your AppID (previously tenant)
        $USER_EMAIL="myemail@email.com";
        $USER_NAME="Karim";
        $USER_IS_MODERATOR=true;
        $USER_AVATAR_URL="";
        $USER_ID=uniqid();
        $LIVESTREAMING_IS_ENABLED=false;
        $RECORDING_IS_ENABLED=false;
        $OUTBOUND_IS_ENABLED=false;
        $TRANSCRIPTION_IS_ENABLED=false;
        $EXP_DELAY_SEC=7200;
        $NBF_DELAY_SEC=10;*/

        if ($userid == null) {
            $userid = uniqid();
        }

        $API_KEY=$api_key;
        $APP_ID=$app_id; // Your AppID (previously tenant)
        $USER_EMAIL=$mail;
        $USER_NAME=$name;
        $USER_IS_MODERATOR=$moderator;
        $USER_AVATAR_URL=$avatar;
        $USER_ID=$userid;
        $LIVESTREAMING_IS_ENABLED=$livestreaming;
        $RECORDING_IS_ENABLED=$recording;
        $OUTBOUND_IS_ENABLED=$outbound;
        $TRANSCRIPTION_IS_ENABLED=$transcription;
        $EXP_DELAY_SEC=$exp_delay_sec;
        $NBF_DELAY_SEC=$nbf_delay_sec;

        ///

        /**
         * We read the JSON Web Key (https://tools.ietf.org/html/rfc7517) 
         * from the private key we generated at https://jaas.8x8.vc/#/apikeys .
         * 
         * @var \Jose\Component\Core\JWK jwk
         */
        $jwk = JWKFactory::createFromKeyFile(__DIR__ . "/rsa-private.key");

        /**
         * Setup the algoritm used to sign the token.
         * @var \Jose\Component\Core\AlgorithmManager $algorithm
         */
        $algorithm = new AlgorithmManager([
            new RS256()
        ]);

        /**
         * The builder will create and sign the token.
         * @var \Jose\Component\Signature\JWSBuilder $jwsBuilder
         */
        $jwsBuilder = new JWSBuilder($algorithm);

        /**
         * Must setup JaaS payload!
         * Change the claims below or using the variables from above!
         */
        $payload = json_encode([
            'iss' => 'chat',
            'aud' => 'jitsi',
            'exp' => time() + $EXP_DELAY_SEC,
            'nbf' => time() - $NBF_DELAY_SEC,
            'room'=> '*',
            'sub' => $APP_ID,
            'context' => [
                'user' => [
                    'moderator' => $USER_IS_MODERATOR ? "true" : "false",
                    'email' => $USER_EMAIL,
                    'name' => $USER_NAME,
                    'avatar' => $USER_AVATAR_URL,
                    'id' => $USER_ID
                ],
                'features' => [
                    'recording' => $RECORDING_IS_ENABLED ? "true" : "false",
                    'livestreaming' => $LIVESTREAMING_IS_ENABLED ? "true" : "false",
                    'transcription' => $TRANSCRIPTION_IS_ENABLED ? "true" : "false",
                    'outbound-call' => $OUTBOUND_IS_ENABLED ? "true" : "false"
                ]
            ]
        ]);

        /**
         * Create a JSON Web Signature (https://tools.ietf.org/html/rfc7515)
         * using the payload created above and the api key specified for the kid claim.
         * 'alg' (RS256) and 'typ' claims are also needed.
         */
        $jws = $jwsBuilder
                ->create()
                ->withPayload($payload)
                ->addSignature($jwk, [
                    'alg' => 'RS256',
                    'kid' => $API_KEY,
                    'typ' => 'JWT'
                ])
                ->build();

        /**
         * We use the serializer to base64 encode into the final token.
         * @var \Jose\Component\Signature\Serializer\CompactSerializer $serializer
         */
        $serializer = new CompactSerializer();
        $token = $serializer->serialize($jws, 0);

        /**
         * Write the token to standard output.
         */
        //echo $token;
        return $token;

    }

    public function encodeJWT($payload, $secret) {
        // Header
        $header = base64_encode(json_encode(['typ' => 'JWT', 'alg' => 'HS256']));
    
        // Payload
        $payload = base64_encode(json_encode($payload));
    
        // Signature
        $signature = hash_hmac('sha256', "$header.$payload", $secret, true);
        $signature = base64_encode($signature);
    
        // JWT
        $jwt = "$header.$payload.$signature";
    
        return $jwt;
    }

    public function decodeJWT($jwt, $secret) {
        // Split JWT into header, payload, and signature
        $parts = explode('.', $jwt);
        $header = $parts[0];
        $payload = $parts[1];
        $signature = $parts[2];
    
        // Verify signature
        $verified_signature = hash_hmac('sha256', "$header.$payload", $secret, true);
        $verified_signature = base64_encode($verified_signature);
    
        if ($verified_signature !== $signature) {
            throw new Exception('Invalid signature');
        }
    
        // Decode payload
        $decoded_payload = json_decode(base64_decode($payload), true);
    
        return $decoded_payload;
    }

    public function isPastOneHour($dateTimeString) {
        // Parse the given date and time string
        $givenDateTime = strtotime($dateTimeString);
    
        // Get the current date and time
        $currentDateTime = time();
    
        // Add one hour to the current date and time
        $futureDateTime = $currentDateTime + (1 * 60 * 60);
    
        // Compare the parsed date and time with the calculated future date and time
        return $givenDateTime < $futureDateTime;
    }

    public function countUnfinishedMeetings($meetings_list) {
        $count = 0;
        foreach ($meetings_list as $meeting) {
            $current_meeting_id = $meeting['meeting_id'];
            $current_meeting = $this->getMeeting($current_meeting_id);
            $meeting_at = $current_meeting['meeting_at'];

            //check if the meeting is finished (+1h of current date time)
            if ( ! $this->isPastOneHour($meeting_at)) {
                $count++;
            }
        }
        return $count;
    }

}
        

