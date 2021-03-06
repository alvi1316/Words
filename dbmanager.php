<?php

    class dbmanager{

        //Function for signup
        function signup($con, $username, $email, $password){
            $qry = "INSERT INTO user (username, email, password) VALUES ('$username', '$email', '$password')";
            if($con->query($qry)===TRUE){
                return TRUE;
            }else{
                return FALSE;
            }
        }
        
        //Function for login
        function login($con, $email, $password){
            $qry = "SELECT password FROM user WHERE email = '$email'";
            $valid = FALSE;
            $result=$con->query($qry);
            $row=$result->fetch_assoc();
            if($row!=null){
                if($row['password']==$password){
                    $valid = TRUE;
                }
            }           
            return $valid;
        }

        //Function to get username
        function getUsername($con, $email){
            $qry = "SELECT username FROM user WHERE email = '$email'";
            $result=$con->query($qry);
            $row=$result->fetch_assoc();
            return $row['username'];
        }

        //Function to get userid
        function getUserId($con, $username){
            $qry = "SELECT u_id FROM user WHERE username = '$username'";
            $result=$con->query($qry);
            $row=$result->fetch_assoc();
            return $row['u_id'];
        }

        //Function to check if email exists
        function emailExists($con, $email){
            $qry = "SELECT * FROM user WHERE email = '$email'";
            $valid = FALSE;
            $result=$con->query($qry);
            if(mysqli_num_rows($result) >= 1){
                $valid = true;
            }
            return $valid;
        }

        //Function to update password
        function resetPassword($con, $email, $password){
            $qry = "UPDATE user SET password = '$password' WHERE email = '$email'";
            $valid = FALSE;
            if ($con->query($qry) === TRUE) {
                $valid = true;
            }
            return $valid;
        }

        //Function to return search results
        function searchUser($con, $keyword){
            $qry = "SELECT 
            A.u_id, 
            A.username, 
            B.name AS reader_badge,
            C.name AS writter_badge 
            FROM user AS A
            INNER JOIN badge AS B
            ON A.reader_badge=B.b_id 
            INNER JOIN badge AS C
            ON A.writter_badge=C.b_id 
            WHERE username LIKE '%$keyword%'";
            $result = $con->query($qry);
            $rows = array();
            while($row = $result->fetch_array()) {
                $rows[] = $row;
            }
            return $rows;
        }

        //Function to return profile user data by username
        function getProfileUser($con, $username){
            $qry = "SELECT A.u_id, A.username, A.reader_rank, A.writter_rank, A.followers, A.email, C1.name AS reader_badge, C2.name AS writter_badge FROM user AS A INNER JOIN badge AS C1 ON A.reader_badge = C1.b_id INNER JOIN badge AS C2 ON A.writter_badge = C2.b_id WHERE username = '$username'";
            $result = $con->query($qry);
            $row = $result->fetch_array();
            return $row;
        }

        //Function that returns if user is following another user
        function isFollowing($con, $follower_id, $following_id){
            $qry = "SELECT status FROM follow WHERE follower_id = $follower_id and following_id = $following_id";
            $result = $con->query($qry);
            $row = $result->fetch_array();
            return $row;
        }

        //Function to follow a user
        function followUser($con, $follower_id, $following_id){
            $success = false;
            $qry = "INSERT INTO follow(follower_id, following_id) VALUES ($follower_id,$following_id) ON DUPLICATE KEY UPDATE  status = true;
            UPDATE user SET followers=followers+1 WHERE u_id=$following_id;";
            if($con->multi_query($qry)!=null){
                $success = true;
            }
            return $success;            
        }

        //Function to unfollow a user
        function unfollowUser($con, $follower_id, $following_id){
            $success = false;
            $qry = "UPDATE follow SET status = false WHERE follower_id = $follower_id AND following_id = $following_id;
            UPDATE user SET followers=followers-1 WHERE u_id=$following_id";
            if($con->multi_query($qry)!=null){
                $success = true;
            }
            return $success; 
        }

        //Function to publish a post
        function publishPost($con, $id, $postTitle, $postText, $count){

            $success = true;

            $qry = "INSERT INTO post(u_id, title, p_text, p_date, p_time, reward) VALUES ($id,'$postTitle','$postText', CURRENT_DATE(), CURRENT_TIME(), (SELECT CASE WHEN($count*0.1>10) THEN floor($count*0.1) ELSE 0 END));
            UPDATE user SET writter_rank = writter_rank+(SELECT CASE WHEN($count*0.1>10) THEN floor($count*0.1) ELSE 0 END) WHERE u_id = $id;
            UPDATE user SET writter_badge=(SELECT MAX(b_id) FROM badge WHERE (SELECT writter_rank FROM user WHERE u_id = $id)>=minimum_rank) WHERE u_id=$id;
            INSERT INTO summery (u_id, s_date, total_write) VALUES($id,DATE(DATE_FORMAT(CURRENT_DATE,'%Y-%m-01')),1) ON DUPLICATE KEY UPDATE  total_write = total_write+1;";
            
            if($con->multi_query($qry)==null){                
                $success = false;
            }
            
            return $success;
        }

        //Function to get all post of user by id
        function getAllUserPost($con, $id){
            $userId = $_SESSION["u_id"];
            $qry = "SELECT A.*, B.vote FROM (SELECT A.p_id, A.u_id, A.title,A.p_text,A.p_date,A.p_time,A.reward,A.upvote,A.downvote,A.comment,B.username FROM post AS A INNER JOIN user AS B ON A.u_id = B.u_id WHERE A.u_id = $id AND A.status = true) AS A LEFT JOIN (SELECT * FROM vote WHERE u_id = $userId) AS B ON A.p_id = B.p_id ORDER BY A.p_date DESC, A.p_time DESC";
            $result = $con->query($qry);
            $rows = array();
            while($row = $result->fetch_array()) {
                $rows[] = $row;
            }
            return $rows;
        }

        //Fuction to get user activity by id
        function getUserActivity($con, $id){
            $qry = "SELECT * FROM summery WHERE u_id = $id AND YEAR(s_date) = YEAR(CURDATE())";
            $result = $con->query($qry);
            $rows = array();
            while($row = $result->fetch_array()) {
                $rows[] = $row;
            }
            return $rows;
        }

        //Function to get all following list
        function getFollowingList($con, $id){
            $qry = "SELECT A.username FROM user AS A INNER JOIN (SELECT following_id FROM follow WHERE follower_id = $id AND status = true) AS B ON A.u_id = B.following_id WHERE A.status = true";
            $result = $con->query($qry);
            $rows = array();
            while($row = $result->fetch_array()) {
                $rows[] = $row;
            }
            return $rows;
        }

        //Function update username by id
        function updateUsername($con, $id, $newUsername){
            $success = false;
            $qry = "UPDATE user SET username='$newUsername' WHERE u_id = $id";
            if($con->query($qry)!=null){
                $success = true;
            }
            return $success; 
        }

        //Function update email by id
        function updateEmail($con, $id, $newUsername){
            $success = false;
            $qry = "UPDATE user SET email='$newUsername' WHERE u_id = $id";
            if($con->query($qry)!=null){
                $success = true;
            }
            return $success; 
        }

        //Function update Password by id
        function updatePassword($con, $id, $newUsername){
            $success = false;
            $qry = "UPDATE user SET password='$newUsername' WHERE u_id = $id";
            if($con->query($qry)!=null){
                $success = true;
            }
            return $success; 
        }

        //Function to deactivate user account
        function deactivateAccount($con, $id){
            $success = false;
            $qry = "UPDATE user SET status=false WHERE u_id = $id";
            if($con->query($qry)!=null){
                $success = true;
            }
            return $success; 
        }
        
        //Function to reactivate user account
        function reactivateAccount($con, $id){
            $success = false;
            $qry = "UPDATE user SET status=true WHERE u_id = $id";
            if($con->query($qry)!=null){
                $success = true;
            }
            return $success; 
        }

        //Function add upvote to post
        function addUpvote($con, $p_id, $u_id){
            $success = false;
            $qry = "INSERT INTO vote(p_id, u_id, vote) VALUES ($p_id, $u_id, 'upvote') ON DUPLICATE KEY UPDATE vote = 'upvote';
                    UPDATE post SET upvote = upvote+1 WHERE p_id = $p_id;";
            if($con->multi_query($qry)!=null){
                $success = true;
            }
            return $success; 
        }

        //Function remove upvote to post
        function removeUpvote($con, $p_id, $u_id){
            $success = false;
            $qry = "UPDATE vote SET vote = 'none' WHERE p_id = $p_id and u_id = $u_id;
                    UPDATE post SET upvote = upvote-1 WHERE p_id = $p_id;";
            if($con->multi_query($qry)!=null){
                $success = true;
            }
            return $success;            
        }

        //Function add downvote to post
        function addDownvote($con, $p_id, $u_id){
            $success = false;
            $qry = "INSERT INTO vote(p_id, u_id, vote) VALUES ($p_id, $u_id, 'downvote') ON DUPLICATE KEY UPDATE vote = 'downvote';
                    UPDATE post SET downvote = downvote+1 WHERE p_id = $p_id;";
            if($con->multi_query($qry)!=null){
                $success = true;
            }
            return $success;
        }

        //Function remove downvote to post
        function removeDownvote($con, $p_id, $u_id){
            $success = false;
            $qry = "UPDATE vote SET vote = 'none' WHERE p_id = $p_id and u_id = $u_id;
                    UPDATE post SET downvote = downvote-1 WHERE p_id = $p_id;";
            if($con->multi_query($qry)!=null){
                $success = true;
            }
            return $success;  
        }

        //Function to get post details by id
        function getPostDetails($con, $p_id, $u_id){
            $qry = "SELECT A.*, B.r_id FROM (SELECT A.*, B.vote FROM (SELECT A.*, B.username FROM post AS A INNER JOIN user AS B ON A.u_id = B.u_id WHERE A.p_id = $p_id AND A.status = true) AS A LEFT JOIN (SELECT * FROM vote WHERE u_id = $u_id) AS B ON A.p_id=B.p_id) AS A LEFT JOIN (SELECT * FROM `read` WHERE u_id = $u_id) AS B ON A.p_id = B.p_id";
            
            $result  = $con->query($qry);
            $row = $result->fetch_array();
            return $row;
        }

        //Function publish comment
        function publishComment($con, $p_id, $u_id, $c_text){
            $success = array();
            $success["success"] = false;
            $success["c_id"] = null;
            $qry = "INSERT INTO comment(p_id, u_id, c_text, c_date, c_time) VALUES ($p_id, $u_id, '$c_text', CURRENT_DATE(), CURRENT_TIME());
            UPDATE post SET comment = comment+1 WHERE p_id = $p_id;";

            if($con->multi_query($qry)!=null){
                $success["success"] = true;
                $success["c_id"] = $con->insert_id;;
            }
            return $success;  
        }

        //Function to get all comments of post by id
        function getComment($con, $p_id){
            $qry = "SELECT A.*, B.username FROM (SELECT * FROM comment WHERE p_id = $p_id and status = true) AS A INNER JOIN user AS B ON A.u_id = B.u_id";
            $result = $con->query($qry);
            $rows = array();
            while($row = $result->fetch_array()) {
                $rows[] = $row;
            }
            return $rows;
        }

        //Function delete comment by id
        function deleteComment($con, $c_id, $p_id){
            $success = false;
            $qry = "UPDATE comment SET status = false WHERE c_id = $c_id;
                    UPDATE post SET comment = comment-1 WHERE p_id = $p_id;";
            if($con->multi_query($qry)!=null){
                $success = true;
            }
            return $success; 
        }

        //Function to publish read
        function publishRead($con, $p_id, $u_id, $reward){            
            $success = true;
            $qry = "INSERT INTO `read`(p_id, u_id) VALUES ($p_id, $u_id);
            UPDATE user SET reader_rank = reader_rank+$reward WHERE u_id = $u_id;
            UPDATE user SET reader_badge=(SELECT MAX(b_id) FROM badge WHERE (SELECT reader_rank FROM user WHERE u_id = $u_id)>=minimum_rank) WHERE u_id=$u_id;
            INSERT INTO summery (u_id, s_date, total_read) VALUES($u_id,DATE(DATE_FORMAT(CURRENT_DATE,'%Y-%m-01')),1) ON DUPLICATE KEY UPDATE  total_read = total_read+1;";
            
            if($con->multi_query($qry)==null){                
                $success = false;
            }
            
            return $success;
        }

        //Function to get all post of following list
        function getAllFollowerPost($con, $u_id, $rangeFinish){
            
            $qry = "SELECT A.vote, B.* FROM (SELECT vote,p_id FROM `vote` WHERE u_id = $u_id) AS A RIGHT JOIN (SELECT A.username, B.* FROM (SELECT username, u_id FROM user WHERE status = true) AS A INNER JOIN (SELECT A.* FROM (SELECT * FROM post WHERE status = true) as A INNER JOIN (SELECT following_id FROM `follow` WHERE follower_id = $u_id AND status = true) AS B ON A.u_id = B.following_id) AS B ON A.u_id = B.u_id) AS B ON A.p_id=B.p_id ORDER BY p_date DESC, p_time DESC LIMIT $rangeFinish";

            $result = $con->query($qry);
            $rows = array();
            while($row = $result->fetch_array()) {
                $rows[] = $row;
            }
            return $rows;
        }

        function loadMoreFollowerPost($con, $u_id, $p_id, $rangeFinish){

            $qry = "SELECT A.* FROM (SELECT A.vote, B.* FROM (SELECT vote,p_id FROM `vote` WHERE u_id = $u_id) AS A RIGHT JOIN (SELECT A.username, B.* FROM (SELECT username, u_id FROM user WHERE status = true) AS A INNER JOIN (SELECT A.* FROM (SELECT * FROM post WHERE status = true) as A INNER JOIN (SELECT following_id FROM `follow` WHERE follower_id = $u_id AND status = true) AS B ON A.u_id = B.following_id) AS B ON A.u_id = B.u_id) AS B ON A.p_id=B.p_id) AS A WHERE A.p_id<$p_id ORDER BY p_date DESC, p_time DESC LIMIT $rangeFinish";

            $result = $con->query($qry);
            $rows = array();
            while($row = $result->fetch_array()) {
                $rows[] = $row;
            }
            return $rows;

        }

        //Function to delete post by id
        function deletePost($con, $u_id, $p_id){
            $success = true;
            $qry = "UPDATE post SET status = false WHERE p_id = $p_id;
            UPDATE user SET writter_rank = writter_rank-(SELECT reward FROM post WHERE p_id = $p_id) WHERE u_id = $u_id;
            UPDATE user SET writter_badge=(SELECT MAX(b_id) FROM badge WHERE (SELECT writter_rank FROM user WHERE u_id = $u_id)>=minimum_rank) WHERE u_id=$u_id;
            UPDATE summery SET total_write = total_write-1 WHERE u_id = $u_id AND s_date = DATE(DATE_FORMAT((SELECT p_date FROM post WHERE p_id = $p_id),'%Y-%m-01'));";

            if($con->multi_query($qry)==null){                
                $success = false;
            }
            return $success;
        }
    }
    
?>