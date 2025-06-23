<?php

require_once __DIR__ . '/../config.php';
include_once __DIR__ . '/user_con.php';
require_once __DIR__ . '/profileController.php';
require_once __DIR__ . '/user_con.php';

class MessagingCon {

    private $tab_name;

    public function __construct($tab_name){
        $this->tab_name = $tab_name;
    }

    public function generateId($id_length){
        $numbers = '0123456789';
        $numbers_length = strlen($numbers);
        $random_id = '';

        // Generate random ID
        for ($i = 0; $i < $id_length; $i++) {
            $random_id .= $numbers[rand(0, $numbers_length - 1)];
        }

        return (string) $random_id; // Ensure the return value is a string
    }

    function extractTimeFromString($datetimeString) {
        $timestamp = strtotime($datetimeString);
        $time = date("H:i", $timestamp);
        return $time;
    }

    public function sendMessage($message) {
        $sql = "INSERT INTO $this->tab_name(id, sender_id, receiver_id, message_content) VALUES (:id, :sender_id, :receiver_id, :message_content)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(
               [
                'id' => $message->get_id(), 
                'sender_id' => $message->get_sender_id(), 
                'receiver_id' => $message->get_receiver_id(), 
                'message_content' => $message->get_message_content()
                ]
            );
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function getTwoUsersMessages($user1, $user2) {
        $sql = "SELECT * FROM $this->tab_name WHERE (sender_id = :user1 AND receiver_id = :user2) OR (sender_id = :user2 AND receiver_id = :user1) ORDER BY date_time";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'user1' => $user1,
                'user2' => $user2
            ]);
            
            // Fetch all rows from the result set as an associative array
            $messages = $query->fetchAll(PDO::FETCH_ASSOC);
            
            return $messages;
        } catch (PDOException $e) {
            // Handle any errors that might occur
            echo "Error: " . $e->getMessage();
        }
    }

    public function getLastTwoUsersMessage($my_profile_id, $other_profile_id) {
        $sql = "SELECT * FROM $this->tab_name 
                WHERE (sender_id = :user1 AND receiver_id = :user2) 
                OR (sender_id = :user2 AND receiver_id = :user1) 
                ORDER BY date_time DESC 
                LIMIT 1"; // Limit to one row, sorted by date_time in descending order
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'user1' => $my_profile_id,
                'user2' => $other_profile_id
            ]);
            
            // Fetch the last row from the result set as an associative array
            $message = $query->fetch(PDO::FETCH_ASSOC);
            
            return $message;
        } catch (PDOException $e) {
            // Handle any errors that might occur
            echo "Error: " . $e->getMessage();
        }
    }

    public function groupMessagesBySender($messages) {
        $packs = []; // List to store packs
        
        $currentPack = []; // Initialize current pack
        
        foreach ($messages as $index => $message) {
            // If it's the first message or sender ID has changed
            if ($index === 0 || $message['sender_id'] !== $messages[$index - 1]['sender_id']) {
                // Add current pack to packs list if it's not empty
                if (!empty($currentPack)) {
                    $packs[] = $currentPack;
                }
                // Start a new pack
                $currentPack = [];
            }
            
            // Add message to current pack
            $currentPack[] = $message;
        }
        
        // Add the last pack to packs list if it's not empty
        if (!empty($currentPack)) {
            $packs[] = $currentPack;
        }
        
        return $packs;
    }

    //experemental function
    public function printGroupedMessages($groupedMessages) {
        foreach ($groupedMessages as $packIndex => $pack) {
            echo "Pack " . ($packIndex + 1) . ":\n";
            foreach ($pack as $messageIndex => $message) {
                echo "Message " . ($messageIndex + 1) . ":\n";
                echo "Sender ID: " . $message['sender_id'] . "\n";
                echo "Receiver ID: " . $message['receiver_id'] . "\n";
                echo "Content: " . $message['message_content'] . "\n";
                echo "Time: " . $message['date_time'] . "\n";
                echo "\n";
            }
            echo "\n";
        }
    }
      
       
    public function generateMessageMeHTML($id, $msgs, $time) {
        $profileController = new ProfileC();

        $profile = $profileController->getProfileById($id);
    
        // Initialize an empty string to store the HTML
        $html = '<li class="conversation-item me">
                    <div class="conversation-item-side">
                        <img class="conversation-item-image" src="data:image/jpeg;base64,' . base64_encode($profile['profile_photo']) . '" alt="">
                    </div>
                    <div class="conversation-item-content">';

                    foreach ($msgs as $msg){
                    
$html.=                 '<div class="conversation-item-wrapper">
                            <div class="conversation-item-box">
                                <div class="conversation-item-text">
                                    <p>' . $msg['message_content'] . '</p>
                                    <div class="conversation-item-time">' . $time . '</div>
                                </div>
                                <!-- <div class="conversation-item-dropdown">
                                    <button type="button" class="conversation-item-dropdown-toggle"><i class="ri-more-2-line"></i></button>
                                    <ul class="conversation-item-dropdown-list">
                                        <li><a href="#"><i class="ri-share-forward-line"></i> Forward</a></li>
                                        <li><a href="#"><i class="ri-delete-bin-line"></i> Delete</a></li>
                                    </ul>
                                </div> -->
                            </div>
                        </div>';

                    }
                        
                        


$html.=           '</div>
                </li>';

        
    
        return $html;
    }

    public function generateMessageOtherHTML($id, $msgs, $time) {
        $profileController = new ProfileC();

        $profile = $profileController->getProfileById($id);
    
        // Initialize an empty string to store the HTML
        $html = '<li class="conversation-item">
                    <div class="conversation-item-side">
                        <img class="conversation-item-image" src="data:image/jpeg;base64,' . base64_encode($profile['profile_photo']) . '" alt="">
                    </div>
                    <div class="conversation-item-content">';

                    foreach ($msgs as $msg){
                    
$html.=                 '<div class="conversation-item-wrapper">
                            <div class="conversation-item-box">
                                <div class="conversation-item-text">
                                    <p>' . $msg['message_content'] . '</p>
                                    <div class="conversation-item-time">' . $time . '</div>
                                </div>
                                <!-- <div class="conversation-item-dropdown">
                                    <button type="button" class="conversation-item-dropdown-toggle"><i class="ri-more-2-line"></i></button>
                                    <ul class="conversation-item-dropdown-list">
                                        <li><a href="#"><i class="ri-share-forward-line"></i> Forward</a></li>
                                        <li><a href="#"><i class="ri-delete-bin-line"></i> Delete</a></li>
                                    </ul>
                                </div> -->
                            </div>
                        </div>';

                    }
                        
                        


$html.=           '</div>
                </li>';

        
    
        return $html;
    }

    public function generateConversationHTML($my_profile_id, $other_profile_id) {
        
        $profileController = new ProfileC();

        $msgs = $this->getTwoUsersMessages($my_profile_id, $other_profile_id);

        $groupedMessages = $this->groupMessagesBySender($msgs);

        foreach ($groupedMessages as $groupedMessage) {

            $lastItem = end($groupedMessage);
            $current_id = $lastItem['sender_id'];
            $date_time = $this->extractTimeFromString($lastItem['date_time']);
            
            if ($current_id == $my_profile_id) {
                echo $this->generateMessageMeHTML($current_id, $groupedMessage, $date_time);
            } else {
                echo $this->generateMessageOtherHTML($current_id, $groupedMessage, $date_time);
            }
        }


        



    }

    public function getLastMessages($profile_id) {
        /*$sql = "SELECT *
                FROM messages
                WHERE sender_id = :profile_id OR receiver_id = :profile_id
                GROUP BY sender_id";*/
        /*$sql = "SELECT m.*
        FROM messages m
        WHERE (m.sender_id, m.date_time) IN (
            SELECT sender_id, MAX(date_time) AS max_date_time
            FROM messages
            WHERE (sender_id = :profile_id OR receiver_id = :profile_id)
            GROUP BY sender_id
        )";
    
        try {
            $db = config::getConnexion();
            $query = $db->prepare($sql);
            $query->execute(array(':profile_id' => $profile_id));
    
            // Fetch all last messages for each friend
            $last_messages = $query->fetchAll();
    
            // Initialize an empty array to store the profiles
            $profiles = array();
    
            // Iterate through each last message
            foreach ($last_messages as $message) {
                // Check if the given profile_id is the sender or receiver
                if ($message['sender_id'] == $profile_id) {
                    // If the profile_id is the sender, add receiver_id to the list
                    $profiles[] = array(
                        "sender_id" => $message['sender_id'],
                        "receiver_id" => $message['receiver_id'],
                        "last_message" => $message['message_content'],
                        "date_time" => $message['date_time'],
                        "seen" => $message['seen'],
                        "you" => 'sender',
                    );
                } elseif ($message['receiver_id'] == $profile_id) {
                    // If the profile_id is the receiver, add sender_id to the list
                    $profiles[] = array(
                        "sender_id" => $message['sender_id'],
                        "receiver_id" => $message['receiver_id'],
                        "last_message" => $message['message_content'],
                        "date_time" => $message['date_time'],
                        "seen" => $message['seen'],
                        "you" => 'receiver',
                    );
                }
            }
    
            return $profiles;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }*/

        $others_ids = $this->listUsersIdsThatSendOrGetMsgs($profile_id);

        // Initialize an empty array to store the profiles
        $profiles = array();

        foreach ($others_ids as $other_id_data) {
            $other_id = $other_id_data['user_id'];
            $message = $this->getLastTwoUsersMessage($profile_id, $other_id);

            // Check if the given profile_id is the sender or receiver
            if ($message['sender_id'] == $profile_id) {
                // If the profile_id is the sender, add receiver_id to the list
                $profiles[] = array(
                    "sender_id" => $message['sender_id'],
                    "receiver_id" => $message['receiver_id'],
                    "last_message" => $message['message_content'],
                    "date_time" => $message['date_time'],
                    "seen" => $message['seen'],
                    "you" => 'sender',
                );
            } elseif ($message['receiver_id'] == $profile_id) {
                // If the profile_id is the receiver, add sender_id to the list
                $profiles[] = array(
                    "sender_id" => $message['sender_id'],
                    "receiver_id" => $message['receiver_id'],
                    "last_message" => $message['message_content'],
                    "date_time" => $message['date_time'],
                    "seen" => $message['seen'],
                    "you" => 'receiver',
                );
            }
        }

        return $profiles;
    }

    public function countUnseenMessages($messages_list, $user_profile_id) {
        $count = 0;
        foreach ($messages_list as $message) {
            if ($message['receiver_id'] == $user_profile_id && $message['seen'] == 'not seen') {
                $count++;
            }
        }
        return $count;
    }

    public function countUnseenMessageNbsForFriendshipe($receiver_id, $sender_id) {
        $sql = "SELECT COUNT(*) AS unseen_count
                FROM messages
                WHERE receiver_id = :receiver_id AND sender_id = :sender_id 
                AND seen = :seen_value";
    
        try {
            $db = config::getConnexion();
            $query = $db->prepare($sql);
            $query->execute(array(':receiver_id' => $receiver_id, ':sender_id' => $sender_id, ':seen_value' => 'not seen'));
    
            $result = $query->fetch(PDO::FETCH_ASSOC);
            
            // If there are no unseen messages, return 0
            if ($result === false) {
                return 0;
            }
    
            return $result['unseen_count'];
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function updateMessagesByReceiverIdAndSenderId($receiver_id, $sender_id, $val)
    {
        $sql = "UPDATE $this->tab_name SET seen = :val WHERE receiver_id = :receiver_id AND sender_id = :sender_id AND seen = 'not seen'";

        $db = config::getConnexion();
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':val', $val);
            $stmt->bindParam(':receiver_id', $receiver_id);
            $stmt->bindParam(':sender_id', $sender_id);
            $stmt->execute();
            $count = $stmt->rowCount(); // Optional: return the number of rows affected
            return $count; // Optional: return the count of rows affected
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function listUsersIdsThatSendOrGetMsgs($profile_id)
    {
        // SQL query to select distinct sender_id and receiver_id
        $sql = "SELECT DISTINCT user_id
                FROM (
                    SELECT sender_id AS user_id
                    FROM messages
                    WHERE receiver_id = :profile_id
                    UNION
                    SELECT receiver_id AS user_id
                    FROM messages
                    WHERE sender_id = :profile_id
                ) AS users";

        // Database connection
        $db = config::getConnexion();

        try {
            // Prepare and execute the SQL query
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':profile_id', $profile_id, PDO::PARAM_INT);
            $stmt->execute();

            // Fetch the results
            $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Return the list of participants
            return $participants;
        } catch (Exception $e) {
            // Handle exceptions
            die('Error:' . $e->getMessage());
        }
    }

    

    
    

}

?>
