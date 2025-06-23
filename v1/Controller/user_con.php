<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/profileController.php';

// for sending mails
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';


require_once 'vendor/autoload.php';



class userCon{

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

    public function userExists($id, $db) {
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

    public function generateUserId($id_length) {
        $db = config::getConnexion();
    
        do {
            $current_id = $this->generateId($id_length);
        } while ($this->userExists($current_id, $db));
    
        return $current_id;
    }

    public function getUser($id)
    {
        $sql = "SELECT * FROM $this->tab_name WHERE id = $id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute();
            $event = $query->fetch();
            return $event;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function listUsers()
    {
        $sql = "SELECT * FROM $this->tab_name";

        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function addUser($user)
    {
        $sql = "INSERT INTO $this->tab_name(id, user_name, email, password, role, verified, banned, date, account_type, need_password_change) VALUES (:id, :user_name, :email, :password, :role, :verified, :banned, :date, :account_type, :need_password_change)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(
               [
                'id' => $user->get_id(), 
                'user_name' => $user->get_user_name(), 
                'email' => $user->get_email(), 
                'password' => $user->get_password(), 
                'role' => $user->get_role(),
                'verified' => $user->get_verified(),
                'banned' => $user->get_banned(),
                'date' => $user->get_date(),
                'account_type' => $user->get_account_type(),
                'need_password_change' => $user->get_need_password_change()
                ]
            );
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function updateUser($user, $id)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare('UPDATE $this->tab_name SET user_name = :user_name, email = :email, password = :password, role = :role, verified = :verified, banned = :banned WHERE id = :id');
            $query->execute(['id' => $id, 'user_name' => $user->get_user_name(), 'email' => $user->get_email(), 'password' => $user->get_password(), 'role' => $user->get_role(), 'verified' => $user->get_verified(), 'banned' => $user->get_banned()]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            $e->getMessage();
            echo($e);
        }
    }

    public function deleteUser($id)
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

    public function userNameExists($user_name) {
        $db = config::getConnexion();

        $sql = "SELECT COUNT(*) as count FROM $this->tab_name WHERE user_name = :user_name";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':user_name', $user_name);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function emailExists($email) {
        $db = config::getConnexion();

        $sql = "SELECT COUNT(*) as count FROM $this->tab_name WHERE email = :email";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function verifLoginInfosByEmail($email, $password) {
        if ($this->emailExists($email) == false){
            return "wrong email";
            exit(); // Make sure to stop further execution after redirection
        }
        $db = config::getConnexion();

        $sql = "SELECT * FROM $this->tab_name WHERE email = :email";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            //if ($result['password'] == $password){
            if (password_verify($password, $result['password'])){
                return $result['id'];
                exit(); // Make sure to stop further execution after redirection
            }
            else{
                return "wrong password";
                exit(); // Make sure to stop further execution after redirection
            }
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function verifLoginInfosByUserName($user_name, $password) {
        if ($this->userNameExists($user_name) == false){
            return "wrong user name";
            exit(); // Make sure to stop further execution after redirection
        }
        $db = config::getConnexion();

        $sql = "SELECT * FROM $this->tab_name WHERE user_name = :user_name";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':user_name', $user_name);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            //if ($result['password'] == $password){
            if (password_verify($password, $result['password'])){
                return $result['id'];
                exit(); // Make sure to stop further execution after redirection
            }
            else{
                return "wrong password";
                exit(); // Make sure to stop further execution after redirection
            }
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function isEmailOrUserName($input){

        // Check if the input matches the email format
        if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
            #echo "Input is an email address.";
            return "email";
        } else {
            // Check if the input contains only letters, numbers, and underscores (assuming it's a username)
            if (preg_match('/^[a-zA-Z0-9_]+$/', $input)) {
                #echo "Input is a username.";
                return "user name";
            } else {
                #echo "Input is neither an email nor a valid username.";
                return "none";
            }
        }
    }

    public function send_password_reset_code($user_input){
        $Username =  'cashogo.tn@gmail.com';
        $Password = 'sznc taqr oqzc lpjk';
        $mail_sender = new MailSender($Username, $Password);


        $email_to_send_to = '';
        $recipient_name = "";
        # get email_to_send_to and recipient_name
        # by user name
        if ($this->isEmailOrUserName($user_input) == "user name"){
            $db = config::getConnexion();

            $sql = "SELECT * FROM $this->tab_name WHERE user_name = :user_name";
            try {
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':user_name', $user_input);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                
                    $email_to_send_to = $result['email'];
                    $recipient_name = $result['user_name'];
                } else {
                    return "wrong user name";
                }

            } catch (Exception $e) {
                die('Error:' . $e->getMessage());
            }
        }
        # by email
        elseif ($this->isEmailOrUserName($user_input) == "email"){
            $db = config::getConnexion();

            $sql = "SELECT * FROM $this->tab_name WHERE email = :email";
            try {
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':email', $user_input);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                
                    $email_to_send_to = $result['email'];
                    $recipient_name = $result['user_name'];
                } else {
                    return "wrong email";
                }

            } catch (Exception $e) {
                die('Error:' . $e->getMessage());
            }
        }


        $reset_code = $this->generateId(5);
        $subject = "Password Reset Code Request [code : " . $reset_code . "]";
        $msg = $mail_sender->generatePasswordResetMessage($recipient_name, $reset_code, "HireUp");

        $mail_send_res = $mail_sender->send_normal_mail($email_to_send_to, $subject, $msg);

        if ($mail_send_res == "mail sent"){

            if (session_status() == PHP_SESSION_NONE) {
                session_set_cookie_params(0, '/', '', true, true);
                session_start();
            }

            $_SESSION['reset code'] = $reset_code; // Set the error message in the session
            return "done";
            exit();
        }
        else {
            return "error : " . $mail_send_res;
        }
    }

    public function send_account_verify_code($user_input){
        $Username =  'cashogo.tn@gmail.com';
        $Password = 'sznc taqr oqzc lpjk';
        $mail_sender = new MailSender($Username, $Password);


        $email_to_send_to = '';
        $recipient_name = "";
        # get email_to_send_to and recipient_name
        # by user name
        if ($this->isEmailOrUserName($user_input) == "user name"){
            $db = config::getConnexion();

            $sql = "SELECT * FROM $this->tab_name WHERE user_name = :user_name";
            try {
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':user_name', $user_input);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                
                    $email_to_send_to = $result['email'];
                    $recipient_name = $result['user_name'];
                } else {
                    return "wrong user name";
                }

            } catch (Exception $e) {
                die('Error:' . $e->getMessage());
            }
        }
        # by email
        elseif ($this->isEmailOrUserName($user_input) == "email"){
            $db = config::getConnexion();

            $sql = "SELECT * FROM $this->tab_name WHERE email = :email";
            try {
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':email', $user_input);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                
                    $email_to_send_to = $result['email'];
                    $recipient_name = $result['user_name'];
                } else {
                    return "wrong email";
                }

            } catch (Exception $e) {
                die('Error:' . $e->getMessage());
            }
        }


        $verification_code = $this->generateId(5);
        $subject = "Account Verification Code [code: " . $verification_code . "]";
        $msg = $mail_sender->generateAccountVerifyMessage($recipient_name, $verification_code, "HireUp");

        $mail_send_res = $mail_sender->send_normal_mail($email_to_send_to, $subject, $msg);

        if ($mail_send_res == "mail sent"){
            
            if (session_status() == PHP_SESSION_NONE) {
                session_set_cookie_params(0, '/', '', true, true);
                session_start();
            }

            $_SESSION['reset code'] = $verification_code; // Set the error message in the session
            return "done";
            exit();
        }
        else {
            return "error : " . $mail_send_res;
        }
    }

    public function sendSharingEmail($send_to, $profile_that_shared){
        $Username =  'cashogo.tn@gmail.com';
        $Password = 'sznc taqr oqzc lpjk';
        $mail_sender = new MailSender($Username, $Password);

        $email_to_send_to = $send_to;

;
        $subject = "Your Post Has Been Shared by " . $profile_that_shared['profile_first_name'] . " " . $profile_that_shared['profile_family_name'];
        $msg = "Hey " . $profile_that_shared['profile_first_name'] . " " . $profile_that_shared['profile_family_name'] . ",

        Quick heads-up – your recent post was shared!
        
        Best,
        " . $profile_that_shared['profile_first_name'] . " " . $profile_that_shared['profile_family_name'];

        $mail_send_res = $mail_sender->send_normal_mail($email_to_send_to, $subject, $msg);

        if ($mail_send_res == "mail sent"){

            return "done";
            exit();
        }
        else {
            return "error : " . $mail_send_res;
        }
    }

    public function sendReclamationMail($send_to, $rec_that_shared){
        $Username =  'cashogo.tn@gmail.com';
        $Password = 'sznc taqr oqzc lpjk';
        $mail_sender = new MailSender($Username, $Password);

        $profileController = new ProfileC();

        $email_to_send_to = $send_to;

        $id_profile = "";
        $id_user = $rec_that_shared['id_user'];
        $id_profile = $this->get_user_profile_id_by_id($id_user);

        if ( $profileController->profileExists($id_profile) == true ) {

            $current_profile = $profileController->getProfileById($id_profile);

            $subject = $current_profile['profile_first_name'] . " " . $current_profile['profile_family_name'] . ", Reclamation Submission Successful";

            $msg = "Hello " . $current_profile['profile_first_name'] . " " . $current_profile['profile_family_name'] . ",

            We wanted to inform you that your reclamation with the ID " . $rec_that_shared['id'] . " has been successfully submitted. Thank you for reaching out to us.
            
            Our team is now reviewing your inquiry, and you can expect to hear back from us shortly with a response.
            
            If you have any urgent concerns or additional information to share, please feel free to let us know.
            
            Best regards," . $current_profile['profile_first_name'] . " " . $current_profile['profile_family_name'];

        }
        else {
            $current_user = $this->getUser($id_user);

            $subject = $current_user['user_name'] . ", Reclamation Submission Successful";

            $msg = "Hello " . $current_user['user_name'] . ",

            We wanted to inform you that your reclamation with the ID " . $rec_that_shared['id'] . "has been successfully submitted. Thank you for reaching out to us.
            
            Our team is now reviewing your inquiry, and you can expect to hear back from us shortly with a response.
            
            If you have any urgent concerns or additional information to share, please feel free to let us know.
            
            Best regards," . $current_user['user_name'];

        }

        $mail_send_res = $mail_sender->send_normal_mail($email_to_send_to, $subject, $msg);

        if ($mail_send_res == "mail sent"){

            return "done";
            exit();
        }
        else {
            return "error : " . $mail_send_res;
        }
    }

    public function sendRepMail($send_to, $rep_that_shared){
        $Username =  'cashogo.tn@gmail.com';
        $Password = 'sznc taqr oqzc lpjk';
        $mail_sender = new MailSender($Username, $Password);

        $profileController = new ProfileC();

        $email_to_send_to = $send_to;

        $id_profile = "";
        $id_user = $rep_that_shared['id_user'];
        $id_profile = $this->get_user_profile_id_by_id($id_user);

        if ( $profileController->profileExists($id_profile) == true ) {

            $current_profile = $profileController->getProfileById($id_profile);

            $subject = $current_profile['profile_first_name'] . " " . $current_profile['profile_family_name'] . ", Response to Your Reclamation";

            $msg = "Hello " . $current_profile['profile_first_name'] . " " . $current_profile['profile_family_name'] . ",

            We hope this email finds you well.
            
            We're writing to inform you that we've responded to your reclamation with the ID " . $rep_that_shared['id_reclamation'] . ". Below is the response from our team:" .
            
            $rep_that_shared['contenu'] . "
            
            If you have any further questions or concerns, please don't hesitate to reach out to us.
            
            Best regards,
            " . $current_profile['profile_first_name'] . " " . $current_profile['profile_family_name'];

        }
        else {
            $current_user = $this->getUser($id_user);

            $subject = $current_user['user_name'] . ", Response to Your Reclamation";

            $msg = "Hello " . $current_user['user_name'] . ",

            We hope this email finds you well.
            
            We're writing to inform you that we've responded to your reclamation with the ID " . $rep_that_shared['id_reclamation'] . ". Below is the response from our team:" .
            
            $rep_that_shared['contenu'] . "
            
            If you have any further questions or concerns, please don't hesitate to reach out to us.
            
            Best regards,
            " . $current_user['user_name'];

        }

        $mail_send_res = $mail_sender->send_normal_mail($email_to_send_to, $subject, $msg);

        if ($mail_send_res == "mail sent"){

            return "done";
            exit();
        }
        else {
            return "error : " . $mail_send_res;
        }
    }

    public function get_user_id_by_username_or_email($user_input){
        if ($this->isEmailOrUserName($user_input) == "user name"){
            $db = config::getConnexion();

            $sql = "SELECT * FROM $this->tab_name WHERE user_name = :user_name";
            try {
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':user_name', $user_input);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    return $result['id'];
                } else {
                    return "wrong user name";
                }

            } catch (Exception $e) {
                die('Error:' . $e->getMessage());
            }
        }
        # by email
        elseif ($this->isEmailOrUserName($user_input) == "email"){
            $db = config::getConnexion();

            $sql = "SELECT * FROM $this->tab_name WHERE email = :email";
            try {
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':email', $user_input);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    return $result['id'];
                } else {
                    return "wrong email";
                }

            } catch (Exception $e) {
                die('Error:' . $e->getMessage());
            }
        }
        else{
            return "none";
        }
    }

    public function get_user_username_by_id($id){
        
        $db = config::getConnexion();

        $sql = "SELECT * FROM $this->tab_name WHERE id = :id";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result['user_name'];
            } else {
                return "error";
            }

        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
        
    }

    public function get_user_email_by_id($id){
        
        $db = config::getConnexion();

        $sql = "SELECT * FROM $this->tab_name WHERE id = :id";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result['email'];
            } else {
                return "error";
            }

        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
        
    }

    public function updateUserPassword($id, $new_password)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE $this->tab_name SET password = :password WHERE id = :id");
            $query->execute(['password' => $new_password, 'id' => $id]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
            return true;
        } catch (PDOException $e) {
            $e->getMessage();
            echo($e);
            return false;
        }
    }

    public function get_user_verified_by_id($id){
        
        $db = config::getConnexion();

        $sql = "SELECT * FROM $this->tab_name WHERE id = :id";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result['verified'];
            } else {
                return "error";
            }

        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
        
    }

    public function updateUserVerified($id, $new_value)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE $this->tab_name SET verified = :verified WHERE id = :id");
            $query->execute(['verified' => $new_value, 'id' => $id]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
            return true;
        } catch (PDOException $e) {
            $e->getMessage();
            echo($e);
            return false;
        }
    }

    public function get_user_banned_by_id($id){
        
        $db = config::getConnexion();

        $sql = "SELECT * FROM $this->tab_name WHERE id = :id";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result['banned'];
            } else {
                return "error";
            }

        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
        
    }

    public function updateUserBanned($id, $new_value)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE $this->tab_name SET banned = :banned WHERE id = :id");
            $query->execute(['banned' => $new_value, 'id' => $id]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
            return true;
        } catch (PDOException $e) {
            $e->getMessage();
            echo($e);
            return false;
        }
    }

    public function get_user_role_by_id($id){
        
        $db = config::getConnexion();

        $sql = "SELECT * FROM $this->tab_name WHERE id = :id";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result['role'];
            } else {
                return "error";
            }

        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
        
    }

    public function updateUserRole($id, $new_value)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE $this->tab_name SET role = :role WHERE id = :id");
            $query->execute(['role' => $new_value, 'id' => $id]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
            return true;
        } catch (PDOException $e) {
            $e->getMessage();
            echo($e);
            return false;
        }
    }

    public function sortUser($by){

        
        $sql = "SELECT * FROM $this->tab_name";
        
        if ($by == "everything"){
            $sql .= " ORDER BY id";
        }
        else{
            $sql .= " ORDER BY $by";
        }

        $db = config::getConnexion();
        try {
            $db = config::getConnexion();
            $query = $db->prepare($sql);
            $query->execute();
        
            $liste = $query->fetchAll(PDO::FETCH_ASSOC);
            //echo "SQL Query: " . $query->queryString;
            return $liste;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }        

    }

    public function searchUser($by, $keyword, $role, $verified, $banned, $need_pass_chn){

        if ($by == "everything"){
            $sql = "SELECT * FROM $this->tab_name WHERE (email LIKE '%$keyword%' OR user_name LIKE '%$keyword%' OR password LIKE '%$keyword%' OR id LIKE '%$keyword%')";
        }
        else{
            $sql = "SELECT * FROM $this->tab_name WHERE $by LIKE '%$keyword%'";
        }

        if ($role != "none"){
            $sql .= " AND role = '$role'";
        }

        if ($verified != "none"){
            $sql .= " AND verified = '$verified'";
        }

        if ($banned != "none"){
            $sql .= " AND banned = '$banned'";
        }

        if ($need_pass_chn != "none"){
            $sql .= " AND need_password_change = '$need_pass_chn'";
        }

        $db = config::getConnexion();
        try {
            $db = config::getConnexion();
            $query = $db->prepare($sql);
            $query->execute();
        
            $liste = $query->fetchAll(PDO::FETCH_ASSOC);
            //echo "SQL Query: " . $query->queryString;
            return $liste;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }        

    }

    public function searchUserSorted($by, $keyword, $role, $verified, $banned, $need_pass_chn){

        if ($by == "everything"){
            $sql = "SELECT * FROM $this->tab_name WHERE (email LIKE '%$keyword%' OR user_name LIKE '%$keyword%' OR password LIKE '%$keyword%' OR id LIKE '%$keyword%')";
        }
        else{
            $sql = "SELECT * FROM $this->tab_name WHERE $by LIKE '%$keyword%'";
        }

        if ($role != "none"){
            $sql .= " AND role = '$role'";
        }

        if ($verified != "none"){
            $sql .= " AND verified = '$verified'";
        }

        if ($banned != "none"){
            $sql .= " AND banned = '$banned'";
        }

        if ($need_pass_chn != "none"){
            $sql .= " AND need_password_change = '$need_pass_chn'";
        }

        // add order by
        if ($by == "everything"){
            $sql .= " ORDER BY id";
        }
        else{
            $sql .= " ORDER BY $by";
        }

        $db = config::getConnexion();
        try {
            $db = config::getConnexion();
            $query = $db->prepare($sql);
            $query->execute();
        
            $liste = $query->fetchAll(PDO::FETCH_ASSOC);
            //echo "SQL Query: " . $query->queryString;
            return $liste;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }        

    }

    public function userExistsInDataBase($id) {
        $db = config::getConnexion();

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

    public function get_user_name_out_of_google_name($name) {

        $string_without_quotes = str_replace(' ', '_', $name);
    
        do {
            $random_number = rand(1, 8);

            $generated_nb = $this->generateId($random_number);

            $user_name = $string_without_quotes . $generated_nb;

        } while ($this->userNameExists($user_name));

        return $user_name;
    
    }

    public function get_account_type_by_id($id){
        
        $db = config::getConnexion();

        $sql = "SELECT * FROM $this->tab_name WHERE id = :id";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result['account_type'];
            } else {
                return "error";
            }

        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
        
    }

    public function get_user_need_password_change_by_id($id){
        
        $db = config::getConnexion();

        $sql = "SELECT * FROM $this->tab_name WHERE id = :id";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result['need_password_change'];
            } else {
                return "error";
            }

        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
        
    }

    public function updateUser_need_password_change($id, $new_value)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE $this->tab_name SET need_password_change = :need_password_change WHERE id = :id");
            $query->execute(['need_password_change' => $new_value, 'id' => $id]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
            return true;
        } catch (PDOException $e) {
            $e->getMessage();
            echo($e);
            return false;
        }
    }

    //GitHub
    public function get_github_link($client_id="d037c92b841fb74c1517"){

        // $client_id = "d037c92b841fb74c1517";
        // $client_secret = "96b9503f0afad35d38f231ba6ec4b8edb3c86769";

        //$link_to_fllow = "https://github.com/login/oauth/authorize?client_id=$client_id&scope=user:read,user:email";
        $link_to_fllow = "https://github.com/login/oauth/authorize?client_id=$client_id&scope=user:read,user:email";
        //return '../../../View/front_office/Sign In & Sign Up/github_signin.php?go_to_github=true';
        return $link_to_fllow;
    }

    // profile management
    public function get_user_profile_id_by_id($id){
        
        $db = config::getConnexion();

        $sql = "SELECT * FROM profile WHERE profile_userid = :id";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result['profile_id'];
            } else {
                return "error";
            }

        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
        
    }

    public function generateNavLink($nb, $link_var){
        $link = "";
        for ($i = 0; $i < $nb; $i++) {
            $link.= "../";
        }
        return $link . $link_var;
    }

    public function get_user_id_by_profile_id($id){
        
        $db = config::getConnexion();

        $sql = "SELECT * FROM profile WHERE profile_id = :id";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result['profile_userid'];
            } else {
                return "error";
            }

        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
        
    }
    

}


class MailSender{
    private $user_name, $password, $email_to_send_to, $subject, $msg;

    public function __construct($user_name, $password){
        $this->user_name = $user_name;
        $this->password = $password;
    }

    public function set_user_name($val){
        $this->user_name = $val;
    }

    public function get_user_name(){
        return $this->user_name;
    }

    public function set_password($val){
        $this->password = $val;
    }

    public function get_password(){
        return $this->password;
    }

    public function set_email_to_send_to($val){
        $this->email_to_send_to = $val;
    }

    public function get_email_to_send_to(){
        return $this->email_to_send_to;
    }

    public function set_subject($val){
        $this->subject = $val;
    }

    public function get_subject(){
        return $this->subject;
    }

    public function set_msg($val){
        $this->msg = $val;
    }

    public function get_msg(){
        return $this->msg;
    }

    public function generatePasswordResetMessage($recipientName, $resetCode, $appName){
        $passwordResetMessage = "Dear $recipientName,\n\n" .
            "You've requested to reset your password for your $appName account. Please use the following code to reset your password:\n\n" .
            "Password Reset Code: $resetCode\n\n" .
            "If you didn't request this reset, please ignore this message.\n\n" .
            "Thank you,\nThe $appName Team";


        return $passwordResetMessage;
    }

    public function generatePasswordResetMessage0($recipientName, $resetCode, $appName){

        // Read HTML content from file
        $htmlContent = file_get_contents(__DIR__ .'/email_template.html');

        // Replace placeholders with actual values
        $htmlContent = str_replace('{recipientName}', $recipientName, $htmlContent);
        $htmlContent = str_replace('{appName}', $appName, $htmlContent);
        $htmlContent = str_replace('{resetCode}', $resetCode, $htmlContent);

        echo "dff : ". __DIR__ . " ff";

        return $htmlContent;
    }

    public function generateAccountVerifyMessage($recipientName, $verificationCode, $appName){
        $verificationMessage = "Dear $recipientName,\n\n" .
            "Thank you for signing up with $appName. To verify your account, please use the following verification code:\n\n" .
            "Verification Code: $verificationCode\n\n" .
            "If you didn't sign up for $appName, please disregard this message.\n\n" .
            "Thank you,\nThe $appName Team";

        return $verificationMessage;
    }

    public function send_normal_mail($email_to_send_to, $email_subject, $email_msg){

        $mail = new PHPMailer(true);

        try{
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username =  $this->get_user_name(); # 'cashogo.tn@gmail.com';
            $mail->Password = $this->get_password(); # 'sznc taqr oqzc lpjk';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom($mail->Username);

            $mail->addAddress($email_to_send_to);

            $mail->isHTML(true);
            $mail->Subject = $email_subject;
            $mail->Body    = $email_msg;

            $mail->send();
            #echo 'Message has been sent';
            return "mail sent";
        }
        catch (Exception $e) {
            #echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return "$mail->ErrorInfo";
        }
    }

}

class GoogleLogin{
    private $client_id, $client_secret, $client_redirect_uri;

    private $DEFAULT_CLIENT_ID = "894714799937-ern1o91j1vnalapk86b8fkerlqp41cik.apps.googleusercontent.com";
    private $DEFAULT_CLIENT_SECRET = "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";

    public function __construct($client = "", $secret = "", $redirect_url = ""){
        // Set default values if the parameters are empty
        $this->client_id = empty($client) ? $this->DEFAULT_CLIENT_ID : $client;
        $this->client_secret = empty($secret) ? $this->DEFAULT_CLIENT_SECRET : $secret;

        //$this->client_redirect_uri = 'http://' . $_SERVER['REQUEST_URI'] . $dir_place . 'callback.php';

        // Determine the relative path from the document root to the directory containing the script
        $dir = str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname($_SERVER['SCRIPT_FILENAME']));
        $base_url = 'http://' . $_SERVER['HTTP_HOST'] . $dir . '/View';

        // Construct the URL to callback.php
        $callback_url = $base_url . '/callback.php';

        //$this->client_redirect_uri = $callback_url;
        //$this->client_redirect_uri = empty($redirect_url) ? $callback_url : $redirect_url;
        $this->client_redirect_uri = "http://localhost/hireup/v1/View/front_office/Sign%20In%20&%20Sign%20Up/authentication-login.php";


        // create Client Request to access Google API
        $this->client = new Google_Client();
        $this->client->setClientId($this->get_client_id());
        $this->client->setClientSecret($this->get_client_secret());
        $this->client->setRedirectUri($this->get_client_redirect_uri());
        $this->client->addScope("email");
        $this->client->addScope("profile");

        //echo "client url: ". $this->get_client_redirect_uri();

    
    }

    public function get_client_id(){
        return $this->client_id;
    }

    public function get_client_secret(){
        return $this->client_secret;
    }

    public function get_client_redirect_uri(){
        return $this->client_redirect_uri;
    }

    public function get_client(){
        return $this->client;
    }

    public function get_auth_url(){
        return $this->client->createAuthUrl();
    }

    public function get_client_infos(){
        
        $client = $this->get_client();

        // authenticate code from Google OAuth Flow
        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            $client->setAccessToken($token['access_token']);

            // get profile info
            $google_oauth = new Google_Service_Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();
            $email =  $google_account_info->email;
            $name =  $google_account_info->name;

            
        }
        else{
            echo "no code";
        }

    }


}




?>