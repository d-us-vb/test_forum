<?php    
     $special_chars = array('@', '!', '#', '$', '%', '^', '&', '*', '(', ')', 
                      '{', '}', '[', ']', '?', '/', '+', '=', '|', "\\", 
                      ':', ';', "'", '"', '-', '_', ',', '<', '.', '>',
                      '`', '~', '0', '1', '2', '3', '4', '5', '6',
                      '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G',
                      'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q',
                      'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
    
    function verify_password_strength($password, $special_char_set) {
        $password_length = strlen($password);
        if($password_length < 13) {
            return array("fail:too_short", false);
        }
        if($password_length > 64) {
            return array("fail:too_long", false);
        }
        if(number_of_special_chars($password, $special_char_set) > 2) {
            return array("success:fewer_than_20_chars,more_than_2_special_chars", true);
        }
        if(number_of_unique_characters($password) > 11 && $password_length > 19) {
            return array("success:length>=20,unique_chars>11", true);
        }
        return array("fail:password_not_strong_enough", false);
    }
    
    function is_in_array($char, $char_set) {
        foreach($char_set as $symbol) {
            if($symbol === $char) {
                return true;
            }
        }
        return false;
    }
    
    function number_of_special_chars($word, $char_set) {
        $count = 0;
        $word_array = str_split($word);
        foreach($word_array as $character) {
            if(is_in_array($character, $char_set)) {
                $count = $count + 1;
            }
        }
        return $count;
    }
    
    function number_of_unique_characters($word) {
        $string_array = str_split($word);
        $unique_characters = array('');
        foreach($string_array as $string_element) {
            if(!is_in_array($string_element, $unique_characters)) {
                $unique_characters[] = $string_element;
            }
        }
        return sizeof($unique_characters);
    }
    
    // this might be unnecessary
    function validate_email_address($email_address) {
        if(filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            return '';
        } else {
            return "email address is invalid";
        }
    }
    
    // enter new user information into database 
    function register_new_user($conn, $name, $hashed_password, $email) {
        
        $sql = "INSERT INTO users(user_name, user_pass, user_email, user_date)
        VALUES(\"$name\", \"$hashed_password\" , \"$email\", NOW())";
        
        if($conn->query($sql) === TRUE) {
            echo "New user created successully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error ."\n";
        }
    }
    
    // connect server
    require 'database_connect.php';
    
    // get contents of textfields
    $username = $conn->real_escape_string($_POST['username']);
    $useremail = $conn->real_escape_string($_POST['useremail']);
    $userpass = $conn->real_escape_string($_POST['userpass']);
    $confirmuserpass = $conn->real_escape_string($_POST['confirmuserpass']);
    
    // permit flags -- these all need to be set to 'true' before an entry in the database is made
    $username_ready = false;
    $useremail_ready = false;
    $userpass_ready = false;
    $confirmuserpass_ready = false;
    
    
    $error_message = "";
    $user_error_message = "";
    
    // ensure there haven't been errors in retrieving the data.
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        echo "REQUEST_METHOD === POST <br /> <br />";
        if(empty($_POST["username"])) {
            $error_message .= "name: 'username' not found. <br />";
        } else {
            $username_ready = true;
        }
        if(empty($_POST["useremail"])) {
            $error_message .= "name: 'useremail' not found. <br />";
        } else {
            $useremail_ready = true;
        }
        if(empty($_POST["userpass"])) {
            $error_message .= "name: 'userpass' not found. <br />";
        } else {
            $userpass_ready = true;
        }
        if(empty($_POST["confirmuserpass"])) {
            $error_message .= "name: 'confirmuserpass' not found. <br />";
        } else {
            $confirmuserpass_ready = true;
        }
    }
    
    var_dump($username_ready);
    var_dump($useremail_ready);
    var_dump($userpass_ready);
    var_dump($confirmuserpass_ready);
    echo "<br /> <br />";
    
    echo $error_message;
    
    // validation
    $username_approved= false;
    $useremail_approved = false;
    $userpass_approved = false;
    
    if($username_ready && $useremail_ready && $userpass_ready && $confirmuserpass_ready) {
        // make sure username contains only allowed characters
        $username_array = str_split($username);
        $username_approved = true;
        foreach ($username_array as $character) {
            $character_code = ord($character);
            if( $character_code < 32 || $character_code == 127) {
                $username_approved = false;
                break;
            }
        }
        
        // validate email
        $useremail_approved = strlen(filter_var($useremail, FILTER_VALIDATE_EMAIL)) !== 0;
        
        // validate password
        if($userpass === $confirmuserpass) {
            $userpass_array = str_split($userpass);
            $userpass_approved = true;
            foreach ($userpass_array as $character) {
                $character_code = ord($character);
                if( $character_code < 32 || $character_code == 127) {
                    $userpass_approved = false;
                    break;
                }
            }   
        } else {
            $userpass_approved = false;
        }

        if($userpass_approved === true) {
            $verify_password_strength_array = verify_password_strength($userpass, $special_chars);
            $userpass_approved = $verify_password_strength_array[1];
            echo $verify_password_strength_array[0];
            var_dump($verify_password_strength_array);
        }
        
        echo '$username_approved: '; var_dump($username_approved); echo '<br />';
        echo '$useremail_approved: '; var_dump($useremail_approved); echo '<br />';
        echo '$userpass_approved: '; var_dump($userpass_approved); echo '<br />';
        
        
        if($username_approved && $useremail_approved && $userpass_approved) {
            register_new_user($conn, $username, password_hash($userpass, PASSWORD_DEFAULT), $useremail);
        } else {
            $user_error_message = "There was a problem processing your request. Make sure that JavaScript is enabled. <br />";
        }
    }
        echo $user_error_message;
        $conn->close();
?>

<script type="text/javascript">
    if(window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>