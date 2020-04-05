<?php
session_start();
//for testing purposes. These will, of course, be set by the user being signed in.
$_SESSION["user_id"] = 4;
$_SESSION["category_id"] = 0;
require 'database_connect.php';

$thread_title = $conn->real_escape_string($_POST["thread_title"]);
$thread_explanation = $conn->real_escape_string($_POST["thread_explanation"]);

echo "<h1>Thread Submit Summary:</h1><br>";
echo "<h2>Thread Title</h2>";
echo $thread_title . "<br> <br>";
echo "<h2>Thread explanation</h2>";
echo $thread_explanation . "<br>";

$query = "INSERT INTO threads VALUES(NULL, " . $_SESSION['user_id'] . 
            ", '" . $thread_title . "', '" . $thread_explanation . "', NOW()" .
            ", " . $_SESSION["category_id"] . ");";

echo $query . "<br>"; // just quick to make sure everything worked.

if($conn->query($query) === TRUE) {
    echo "Your post was submitted successfully.<br>";
    echo "You can see it now, or return to your profile.<br>";
} else {
    echo "An error occurred. We're trying to email you your post before it's lost. Try again later.<br>";
    echo "INFO: " . $query .": " . $conn->error . "<br>";
}
?>