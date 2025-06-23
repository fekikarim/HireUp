<?php

require_once __DIR__ . '/../config.php';
include_once __DIR__ . '/user_con.php';
require_once __DIR__ . '/profileController.php';

class FriendshipCon {

    private $tab_name;

    public function __construct($tab_name){
        $this->tab_name = $tab_name;
    }

    public function getFriendship($id)
    {
        $sql = "SELECT * FROM $this->tab_name WHERE id = :id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
            $friendship = $query->fetch();
            return $friendship;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function listFriendships()
    {
        $sql = "SELECT * FROM $this->tab_name";

        $db = config::getConnexion();
        try {
            $list = $db->query($sql);
            return $list;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    function addFriendship($friendship)
    {
        $sql = "INSERT INTO $this->tab_name(id, profile1, profile2, status, sender_profile, time) VALUES (:id, :profile1, :profile2, :status, :sender_profile, :time)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(
               [
                'id' => $friendship->get_id(),
                'profile1' => $friendship->get_profile1(),
                'profile2' => $friendship->get_profile2(),
                'status' => $friendship->get_status(),
                'sender_profile' => $friendship->get_sender_profile(),
                'time' => $friendship->get_time(),
               ]
            );
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function updateFriendship($friendship, $id)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE $this->tab_name SET profile1 = :profile1, profile2 = :profile2, status = :status WHERE id = :id");
            $query->execute([
                'id' => $id, 
                'profile1' => $friendship->get_profile1(),
                'profile2' => $friendship->get_profile2(),
                'status' => $friendship->get_status(),
            ]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            $e->getMessage();
            echo($e);
        }
    }

    function deleteFriendship($id)
    {
        $sql = "DELETE FROM $this->tab_name WHERE id = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);

        try {
            $req->execute();
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    function areFriends($profile1, $profile2) {
    
        // Assuming your table is named "friendships"
        $sql = "SELECT * FROM friendships WHERE (profile1 = :profile1 AND profile2 = :profile2) OR (profile1 = :profile2 AND profile2 = :profile1)";
    
        try {
            $db = config::getConnexion();
            $query = $db->prepare($sql);
            $query->execute(array(':profile1' => $profile1, ':profile2' => $profile2));
    
            // If there's at least one row in the result set, the profiles are friends
            if ($query->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    function getFriendshipStatus($profile1, $profile2) {
    
        // Assuming your table is named "friendships"
        $sql = "SELECT * FROM friendships WHERE (profile1 = :profile1 AND profile2 = :profile2) OR (profile1 = :profile2 AND profile2 = :profile1)";
    
        try {
            $db = config::getConnexion();
            $query = $db->prepare($sql);
            $query->execute(array(':profile1' => $profile1, ':profile2' => $profile2));
    
            // If there's at least one row in the result set, the profiles are friends
            if ($query->rowCount() > 0) {
                // Check if the friendship is confirmed or pending
                $friendship = $query->fetch();
                if ($friendship['status'] == 'accepted') {
                    return 'friends';
                } else {
                    return 'pending';
                }
            } else {
                return 'not friends';
            }
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    function getFriendshipSender($profile1, $profile2) {
    
        // Assuming your table is named "friendships"
        $sql = "SELECT * FROM friendships WHERE (profile1 = :profile1 AND profile2 = :profile2) OR (profile1 = :profile2 AND profile2 = :profile1)";
    
        try {
            $db = config::getConnexion();
            $query = $db->prepare($sql);
            $query->execute(array(':profile1' => $profile1, ':profile2' => $profile2));
    
            // If there's at least one row in the result set, the profiles are friends
            if ($query->rowCount() > 0) {
                // Check if the friendship is confirmed or pending
                $friendship = $query->fetch();
                return $friendship['sender_profile'];
            } else {
                return 'none';
            }
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    function getFriendshipId($profile1, $profile2) {
    
        // Assuming your table is named "friendships"
        $sql = "SELECT * FROM friendships WHERE (profile1 = :profile1 AND profile2 = :profile2) OR (profile1 = :profile2 AND profile2 = :profile1)";
    
        try {
            $db = config::getConnexion();
            $query = $db->prepare($sql);
            $query->execute(array(':profile1' => $profile1, ':profile2' => $profile2));
    
            // If there's at least one row in the result set, the profiles are friends
            if ($query->rowCount() > 0) {
                // Check if the friendship is confirmed or pending
                $friendship = $query->fetch();
                return $friendship['id'];
            } else {
                return 'none';
            }
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    function acceptFriendship($id, $time)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("UPDATE $this->tab_name SET status = :status, time = :time WHERE id = :id");
            $query->execute([
                'id' => $id, 
                'status' => 'accepted',
                'time' => $time,
            ]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            $e->getMessage();
            echo($e);
        }
    }

    function getFriendRequests($profile_id) {
    
        $sql = "SELECT * FROM friendships WHERE (profile1 = :profile_id OR profile2 = :profile_id) AND status = 'pending' AND sender_profile != :profile_id";
    
        try {
            $db = config::getConnexion();
            $query = $db->prepare($sql);
            $query->execute(array(':profile_id' => $profile_id));
    
            // Fetch all friend requests
            $friend_requests = $query->fetchAll();
    
            // Initialize an empty array to store the profiles
            $profiles = array();
    
            // Iterate through each friend request
            foreach ($friend_requests as $request) {
                // Check if the given profile_id is in profile1 or profile2
                if ($request['profile1'] == $profile_id) {
                    // If profile1 matches, add profile2 to the list
                    $profiles[] = $profiles[] = array(
                        "profile_id" => $request['profile2'],
                        "friendship_id" => $request['id']
                    );
                } elseif ($request['profile2'] == $profile_id) {
                    // If profile2 matches, add profile1 to the list
                    $profiles[] = array(
                        "profile_id" => $request['profile1'],
                        "friendship_id" => $request['id']
                    );
                }
            }
    
            return $profiles;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    function getFriends($profile_id) {
    
        $sql = "SELECT * FROM friendships WHERE (profile1 = :profile_id OR profile2 = :profile_id) AND status = 'accepted'";
    
        try {
            $db = config::getConnexion();
            $query = $db->prepare($sql);
            $query->execute(array(':profile_id' => $profile_id));
    
            // Fetch all friend requests
            $friend_requests = $query->fetchAll();
    
            // Initialize an empty array to store the profiles
            $profiles = array();
    
            // Iterate through each friend request
            foreach ($friend_requests as $request) {
                // Check if the given profile_id is in profile1 or profile2
                if ($request['profile1'] == $profile_id) {
                    // If profile1 matches, add profile2 to the list
                    $profiles[] = $request['profile2'];
                } elseif ($request['profile2'] == $profile_id) {
                    // If profile2 matches, add profile1 to the list
                    $profiles[] = $request['profile1'];
                }
            }
    
            return $profiles;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    function generateProfileFriendsHTML($profiles, $max_val) {

        $userC = new userCon("user");
        $profileController = new ProfileC();

        $html = ''; // Initialize an empty string to store the generated HTML markup
    
        // Determine the maximum number of iterations based on the minimum of max_val and the actual number of profiles
        $iterations = min($max_val, count($profiles));
    
        // Loop through each profile
        for ($i = 0; $i < $iterations; $i++) {

            $profile = $profileController->getProfileById($profiles[$i]);
            $user = $userC->getUser($profile['profile_userid']);
            
            // Generate HTML markup for the profile
            $html .= '<div class="friend-item justify-content-center">';
            $html .= '<a href="' . 'profile.php?profile_id=' . $profile['profile_id'] . '">';
            //$html .= '<img src="' . 'data:image/jpeg;base64,' . base64_encode($profile['profile_photo']) . '" alt="' . $user['user_name'] . '" class="friend-profile-picture ml-3">';
            $html .= '<img src="' . 'data:image/jpeg;base64,' . base64_encode($profile['profile_photo']) . '" alt="' . $profile['profile_first_name'] . " " . $profile['profile_family_name'] . '" class="friend-profile-picture ml-3">';
            $html .= '</a>';
            $html .= '<p class="friend-name">';
            //$html .= '<a href="' . 'profile.php?profile_id=' . $profile['profile_id'] . '" class="profile-link">' . $user['user_name'] . '</a>';
            $html .= '<a href="' . 'profile.php?profile_id=' . $profile['profile_id'] . '" class="profile-link">' . $profile['profile_first_name'] . " " . $profile['profile_family_name'] . '</a>';
            $html .= '</p>';
            $html .= '</div>';
            $html .= '<hr>';
        }
    
        return $html;
    }

    function generateProfileFriendsPageHTML($profiles, $main_profile_id)
    {
        $html = ''; // Initialize an empty string to store the generated HTML markup

        $profileController = new ProfileC();
        $friendshipC = new FriendshipCon("friendships");

        // Loop through each profile
        foreach ($profiles as $prof) {

            $profile = $profileController->getProfileById($prof);


            // Generate HTML markup for the profile
            $html .= '<div class="col-md-6 col-xl-4 mb-3">';
            $html .= '<div class="card h-100">';
            $html .= '<div class="card-body d-flex align-items-center gap-3">';
            $html .= '<a href="profile.php?profile_id=' . $profile['profile_id'] . '">';
            $html .= '<img src="data:image/jpeg;base64,' . base64_encode($profile['profile_photo']) . '" alt="' . $profile['profile_first_name'] . ' ' . $profile['profile_family_name'] . '" class="friend-profile-picture ml-3">';
            $html .= '</a>';

            $html .= '<div class="flex-grow-1">';
            $html .= '<a href="profile.php?profile_id=' . $profile['profile_id'] . '" class="profile-link"><h5 class="fw-semibold mb-0">' . $profile['profile_first_name'] . ' ' . $profile['profile_family_name'] . '</h5></a>';
            $html .= '<span class="fs-7 d-flex align-items-center">' . $profile['profile_current_position'] .  '</span>';

            $follow_status = $friendshipC->getFriendshipStatus($profile['profile_id'], $main_profile_id);

            $html .= '<div class="mt-2">';
            if ($follow_status == 'friends') {
                $html .= '<button class="btn btn-secondary btn-sm rounded-pill px-4 py-2 me-2" onclick="window.location.href = \'./../friendships/remove_friendship.php?profile_id=' . $profile['profile_id'] . '\'"><i class="fa fa-check me-2"></i><b>Following</b></button>';
            } elseif ($follow_status == 'pending') {
                if ($friendshipC->getFriendshipSender($profile['profile_id'], $main_profile_id) == $main_profile_id) {
                    $html .= '<button class="btn btn-primary btn-sm rounded-pill px-4 py-2 me-2" onclick="window.location.href = \'./../friendships/remove_friendship.php?profile_id=' .  $profile['profile_id'] . '\'"><i class="fas me-2" style="font-size: 15px">&#xf110;</i><b>Pending</b></button>';
                } else {
                    $html .= '<button class="btn btn-accept-user btn-sm rounded-pill px-4 py-2 me-2" onclick="window.location.href = \'./../friendships/accept_friendship.php?profile_id=' .  $profile['profile_id'] . '\'"><i class="fas me-2">&#xF055;</i><b>Accept</b></button>';
                    $html .= '<button class="btn btn-refuse-user btn-sm rounded-pill px-4 py-2" onclick="window.location.href = \'./../friendships/remove_friendship.php?profile_id=' .  $profile['profile_id'] . '\'"><i class="fas me-2">&#xf057;</i><b>Refuse</b></button>';
                }
            } else {
                $html .= '<button class="btn btn-primary btn-sm rounded-pill px-4 py-2 me-2" onclick="window.location.href = \'./../friendships/add_friendship.php?profile_id=' . $profile['profile_id'] . '\'"><i class="fa fa-plus me-2"></i><b>Follow</b></button>';
            }
            $html .= '</div>'; // Close mt-2
            $html .= '</div>'; // Close flex-grow-1

            $html .= '</div>'; // Close card-body
            $html .= '</div>'; // Close card
            $html .= '</div>'; // Close col
        }

        return $html;
    }

}

?>
