<?php
    session_start();
    ini_set('display_errors', '1');
    require 'database_connect.php';
    $error = "";
    if(isset($_POST["username"])) {
        $user_name = $conn->real_escape_string($_POST["username"]);
    } else {
        $error .= "Username field was empty; please enter your username.<br>";
    }
    if(isset($_POST["password"])) {
        $password = $conn->real_escape_string($_POST["password"]);
    } else {
        $error .= "Password field was emtpy; please supply your password.<br>";
    }
    
    if($error) {
        echo $error;
        echo 'Click <a href="forum_index.php">here</a> to try logging in again.';
        die();
    }
    
    $query = "SELECT 
                `user_id`,
                `user_name`,
                `user_pass`,
                `user_privilege_level`
            FROM 
                `users`
            WHERE
                `user_name` = '$user_name';";
    $result = $conn->query($query);
    if($result) {
        $row = $result->fetch_assoc();
        if(password_verify($password, $row['user_pass'])) {
            $_SESSION["signed_in"] = true;
            header("location: ./home.php");
        } else {
            echo 'The password you provided is incorrect for that username. Please try again by clicking <a href="./forum_index.php">here</a>';
        }
    } else {
        echo "There was a problem logging you in. Please try again later, or contact one of the site admins. <br>";
        echo $query . " _________ " . $conn->error . "<br>";
    }
?>




































