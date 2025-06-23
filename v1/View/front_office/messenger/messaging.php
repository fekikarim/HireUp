<?php

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/', '', true, true);
    session_start();
  }

// Include database connection and profile controller
require_once __DIR__ . '/../../../Controller/profileController.php';
require_once __DIR__ . '/../../../Controller/friendshipsCon.php';
include_once __DIR__ . '/../../../Controller/user_con.php';
include_once __DIR__ . '/../../../Controller/messaging_con.php';

$friendshipC = new FriendshipCon("friendships");

// Initialize profile controller
$profileController = new ProfileC();
$userC = new userCon("user");
$msgC = new MessagingCon("messages");

// Get profile ID from the URL
$profile_id = "";

if (isset($_SESSION['user id'])) {

  $user_id = htmlspecialchars($_SESSION['user id']);

  // Get profile ID from the URL
  $profile_id = $profileController->getProfileIdByUserId($user_id);

}else{
    if (isset($_GET['profile_id'])) {
        $profile_id = $_GET['profile_id'];
    }
}

// Fetch profile data from the database
$profile = $profileController->getProfileById($profile_id);

$user_friends = array();
$user_friends = $friendshipC->getFriends($profile_id);

//var_dump($user_friends);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="./../../../front office assets\images\HireUp_icon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="./../profiles_management/assets/css/tailwindcss-colors.css">
    <link rel="stylesheet" href="./../profiles_management/assets/css/messaging.css">
    <title>HireUp Chat</title>
    <script src="https://kit.fontawesome.com/86ecaa3fdb.js" crossorigin="anonymous"></script>

    <!-- voice recognation -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>

</head>
<body>

    <?php 
        $block_call_back = 'false';
        $access_level = "else";
        include('./../../../View/callback.php')  
    ?>

    <!-- start: Chat -->
    <section class="chat-section">
        <div class="chat-container">
            <!-- start: Sidebar -->
            <aside class="chat-sidebar">
                <a title="#" href="#" class="chat-sidebar-logo">
                    <i class="ri-chat-1-fill"></i>
                </a>
                <ul class="chat-sidebar-menu">
                    <li class="active"><a title="#" href="#" data-title="Chats"><i class="ri-chat-3-line"></i></a></li>
                    <!-- <li><a title="#" href="#" data-title="Contacts"><i class="ri-contacts-line"></i></a></li>
                    <li><a title="#" href="#" data-title="Documents"><i class="ri-folder-line"></i></a></li>
                    <li><a title="#" href="#" data-title="Settings"><i class="ri-settings-line"></i></a></li> -->
                    <li class="chat-sidebar-profile">
                        <button title="#" type="button" class="chat-sidebar-profile-toggle">
                            <img src="data:image/jpeg;base64,<?= base64_encode($profile['profile_photo']) ?>" alt="<?= $profile['profile_first_name'] ?>">
                        </button>
                        <ul class="chat-sidebar-profile-dropdown">
                            <li><a href="./../profiles_management/profile.php?profile_id=<?php echo $profile['profile_id'] ?>"><i class="ri-user-line"></i> Profile</a></li>
                            <li><a href="./../Sign In & Sign Up/logout.php"><i class="ri-logout-box-line"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </aside>
            <!-- end: Sidebar -->
            <!-- start: Content -->
            <div class="chat-content">
                <!-- start: Content side -->
                <div class="content-sidebar">
                    <div class="content-sidebar-title">
                    <div class="conversation-form">
                    <button type="button" class="conversation-form-button" onclick="window.location.href = './../profiles_management/profile.php'"><i class="fa-solid fa-arrow-left"></i></button>
                    <div class="conversation-form">Chat</div>
                    </div>
                    </div>
                    <!-- <form action="" class="content-sidebar-form">
                        <input type="search" class="content-sidebar-input" placeholder="Search...">
                        <button title="#" type="submit" class="content-sidebar-submit"><i class="ri-search-line"></i></button>
                    </form> -->
                    <div class="content-messages">
                        <ul class="content-messages-list" id="content-messages-list">
                            <li class="content-message-title"><span>Followers</span></li>
                            
                            <?php
                            foreach ($user_friends as $user_friend) {

                                // Fetch profile data from the database
                                $current_profile = $profileController->getProfileById($user_friend);
                                //var_dump($current_profile);
                                //$current_user = $userC->getUser($current_profile['profile_userid']);
                                $user_profile_id = $profileController->getProfileIdByUserId($user_id);

                                $last_msg_data = $msgC->getLastTwoUsersMessage($user_profile_id, $current_profile['profile_id']);

                                $last_msg_sent_time = "";
                                if ($last_msg_data){
                                    $last_msg_sent_time = $msgC->extractTimeFromString($last_msg_data['date_time']);
                                }

                                $unseen_msg_nb = 0;
                                $unseen_msg_nb = $msgC->countUnseenMessageNbsForFriendshipe($user_profile_id, $current_profile['profile_id']);

                                if ($last_msg_data){
                                    if ($last_msg_data['sender_id'] == $user_profile_id){
                                        $last_msg_data_to_display = "You: ";
                                    }
                                    else{
                                        $last_msg_data_to_display = $current_profile['profile_first_name']. ": ";
                                    }
                                    $last_msg_data_to_display .= $last_msg_data['message_content'];
                                } else {
                                    $last_msg_data_to_display = "No messages yet";
                                }

                            ?>

                                <li data-info="<?php echo strval($current_profile['profile_id']); ?>">
                                    <a href="#" onclick="loadPage('<?php echo strval($current_profile['profile_id']); ?>')" data-conversation="#conversation-1">
                                        <img class="content-message-image" src="data:image/jpeg;base64,<?= base64_encode($current_profile['profile_photo']) ?>" alt="">
                                        <span class="content-message-info">
                                            <!-- <span class="content-message-name"><?php //echo $current_user['user_name']; ?></span> -->
                                            <span class="content-message-name"><?php echo $current_profile['profile_first_name'] . " " . $current_profile['profile_family_name']; ?></span>
                                            <span class="content-message-text"><?php echo $last_msg_data_to_display; ?></span>
                                        </span>
                                        <span class="content-message-more">
                                            <?php if ($unseen_msg_nb > 0){ ?>
                                                <span class="content-message-unread" id="msg_nb_<?php echo $current_profile['profile_id'] ; ?>"><?php echo $unseen_msg_nb; ?></span>
                                            <?php } else { ?>
                                                <span class="content-message-unread" style="display: none;" id="msg_nb_<?php echo $current_profile['profile_id'] ; ?>"><?php echo $unseen_msg_nb; ?></span>
                                            <?php } ?>
                                            <span class="content-message-time"><?php echo $last_msg_sent_time; ?></span>
                                        </span>
                                    </a>
                                </li>

                            <?php

                            }
                            
                            ?>

                        </ul>
                        
                    </div>
                </div>
                <!-- end: Content side -->
                <!-- start: Conversation -->
                <div class="conversation conversation-default <?php echo isset($_GET['go_back_to']) ? '' : 'active'; ?>">
                    <i class="ri-chat-3-line"></i>
                    <p>Select chat and view conversation!</p>
                </div>
                <div class="conversation <?php echo isset($_GET['go_back_to']) ? 'active' : ''; ?>" id="conversation-1">

                <?php if (!isset($_GET['go_back_to'])) { ?>
                    <iframe id="iframe" frameborder="0" src="message_frame.php" style="width: 100%; height: 100%; display: flex;"></iframe>
                <?php } else {?>
                    <iframe id="iframe" frameborder="0" src=<?php echo urldecode($_GET['go_back_to']) ; ?> style="width: 100%; height: 100%; display: flex;"></iframe>
                <?php }?>
                <form id="messageSendingForm" action="send_message.php?user_profile_id=<?php echo $profile['profile_id'] ; ?>" method="post">
                    <input type="hidden" name="msg_to_profile_id" id="msg_to_profile_id">
                    <input type="hidden" name="go_back_to" id="go_back_to">
                    <div class="conversation-form">
                            <div class="conversation-form-group">
                                <textarea class="conversation-form-input" rows="1" placeholder="Type here..." id="msg_text_area" name="msg_text_area" onkeydown="if(event.key === 'Enter'){event.preventDefault(); if(this.value.trim() !== '') { this.form.submit(); }};"></textarea>
                                <!-- <button type="button" class="conversation-form-record"><i class="ri-mic-line"></i></button> -->
                            </div>
                            <button type="submit" class="conversation-form-button conversation-form-submit"><i class="ri-send-plane-2-line"></i></button>
                        </div>
                    </form>
                </div>
                <!-- end: Conversation -->
            </div>
            <!-- end: Content -->
        </div>
    </section>
    <!-- end: Chat -->
    
    <script>
      function updateMessages(receiverId, senderId, newVal) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update_messages_to_seen.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4) {
            if (xhr.status === 200) {
              console.log("Messages updated successfully");
              console.log(xhr.responseText);

              updateMsgsNbVisibility('msg_nb_'+senderId, 'none');
              // Optionally, you can do something after successful update
            } else {
              console.error("Error updating notifications:", xhr.statusText);
            }
          }
        };
        xhr.onerror = function () {
          console.error("Request failed");
        };
        var params = "receiver_id=" + encodeURIComponent(receiverId) + "&sender_id=" + encodeURIComponent(senderId) + "&new_val=" + encodeURIComponent(newVal);
        xhr.send(params);
      }

      function updateMsgsNbVisibility(msg_nb_id, newVal) {
        
        // Check if the element exists
        var followBadge = document.getElementById(msg_nb_id);
        if (followBadge) {
            followBadge.style.display = newVal; // Setting display to an empty string will reset it to the default value (usually "inline" or "block")
        }
    
      }


    </script>

    <script>
        function loadPage(profile_id) {
            
            document.getElementById('iframe').src = "message_frame.php?profile_id=" + profile_id;
            document.getElementById('msg_to_profile_id').value = profile_id;
            document.getElementById('go_back_to').value = document.getElementById('iframe').src;

            // make the msg seen
            updateMessages('<?php echo $profile_id ;?>', profile_id, 'seen');
        }

        <?php if (isset($_GET['go_back_to'])) {?>
                loadPage('<?php echo $_GET["reciever_id"] ;?>');
            
        <?php    
            }
        ?>
    </script>

    <script>
        
        function getMsgsNb(user_profile_id, other_profile_id, callback) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "get_messages_nb.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        // Call the callback function with the response text
                        callback(xhr.responseText);
                    } else {
                        console.error("Error updating notifications:", xhr.statusText);
                        // Call the callback function with an error indicator
                        
                    }
                }
            };
            xhr.onerror = function () {
                console.error("Request failed");
                // Call the callback function with an error indicator
                
            };
            var params = "user_profile_id=" + encodeURIComponent(user_profile_id) + "&other_profile_id=" + encodeURIComponent(other_profile_id);
            xhr.send(params);
        }

        function updateContactList() {
            var contact_list = document.getElementById('content-messages-list');

            // Get all <li> elements inside the <ul>
            var listItems = contact_list.getElementsByTagName("li");

            // Loop through each <li> element
            for (var i = 0; i < listItems.length; i++) {
                // Check if the current <li> element has the class "content-message-title"
                if (!listItems[i].classList.contains("content-message-title")) {
                    // Output the text content of the <li> element (if it doesn't have the class)
                    var element = listItems[i];
                    var data = element.dataset.info;

                    var other_profile_id = data;
                    var user_profile_id = '<?php echo $profile_id ;?>';

                    // Call getMsgsNb with a callback function
                    getMsgsNb(user_profile_id, other_profile_id, function(response) {
                        //console.log(response); // Handle the response here
                        // Update the HTML content of the element with the received response
                        if (parseInt(response) > 0){
                            old_res = document.getElementById('msg_nb_'+other_profile_id).innerHTML != response
                            if (old_res){
                                if (old_res != response){
                                    old_res = document.getElementById('msg_nb_'+other_profile_id).innerHTML = response;
                                    console.log("Messages updated successfully");
                                    updateMsgsNbVisibility('msg_nb_'+other_profile_id, '');
                                }
                            } 
                        }
                    });
                }
            }   
        }
            

    </script>

    <script>
        // Automatically reload the page every 2 seconds
        setInterval(function () {
            updateContactList();
        }, 3000);
    </script>
    
    <script src="./../profiles_management/assets/js/messaging.js"></script>

    <!-- voice recognation -->
	<script type="text/javascript" src="./../../../View\front_office\voice recognation\voice_recognation_and_navigation.js"></script>
</body>
</html>