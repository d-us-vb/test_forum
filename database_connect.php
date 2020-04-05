<?php
    $host_name = 'localhost';
    $database_username = 'anallege_forum';
    $database_name = 'anallege_forum';
    $database_password = '******';
    
    // create connection
    $conn = new mysqli($host_name, $database_username, $database_password, 
    $database_name);
    
    // check connection
    if($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    //echo "Connected to database successfully. <br /> <br />";
    
?>