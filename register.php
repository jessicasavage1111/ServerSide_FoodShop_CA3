<?php

//register.php

/**
 * Start the session.
 */
session_start();

/**
 * Include ircmaxell's password_compat library.
 */
require 'libary-folder/password.php';

/**
 * Include our MySQL connection.
 */
require 'login_connect.php';
?>
<div class="container">
<?php
include('includes/header.php');
?>

<?php
//If the POST var "register" exists (our submit button), then we can
//assume that the user has submitted the registration form.
if(isset($_POST['register'])){
    
    //Retrieve the field values from our registration form.
    $username = !empty($_POST['username']) ? trim($_POST['username']) : null;
    $pass = !empty($_POST['password']) ? trim($_POST['password']) : null;
    $confirm = !empty($_POST['confirm']) ? trim($_POST['confirm']) : null;
    
    //TO ADD: Error checking (username characters, password length, etc).
    //Basically, you will need to add your own error checking BEFORE
    //the prepared statement is built and executed.
    
    //Now, we need to check if the supplied username already exists.
    
    //Construct the SQL statement and prepare it.
    $sql = "SELECT COUNT(username) AS num FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    
    //Bind the provided username to our prepared statement.
    $stmt->bindValue(':username', $username);
    
    //Execute.
    $stmt->execute();
    
    //Fetch the row.
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    //If the provided username already exists - display error.
    //TO ADD - Your own method of handling this error. For example purposes,
    //I'm just going to kill the script completely, as error handling is outside
    //the scope of this tutorial.
    if($row['num'] > 0){
        die('That username already exists!');
    }
    
    //Hash the password as we do NOT want to store our passwords in plain text.
    $passwordHash = password_hash($pass, PASSWORD_BCRYPT, array("cost" => 12));
    
    //Prepare our INSERT statement.
    //Remember: We are inserting a new row into our users table.
    $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
    $stmt = $pdo->prepare($sql);
    
    //Bind our variables.
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':password', $passwordHash);

    //Execute the statement and insert the new account.
    if($pass === $confirm){
        $result = $stmt->execute();
        echo 'Thank you for registering with our website.';
    }
    else{
        echo 'User could not be created. Please check your username, password and confirm password';
    }
    
}

?>

<?php?>
        <h1>Register</h1>
        <form action="register.php" method="post" id="add_record_form">
            <label for="username">Username</label>
            <input type="text" id="username" name="username"><br><br>
            <label for="password">Password</label>
            <input type="text" id="password" name="password"><br><br>
            <label for="confirm">Confirm Password</label>
            <input type="text" id="confirm" name="confirm"><br><br>
            <input type="submit" name="register" value="Register" class="green-button"></button><br>
        </form>
        <?php
include('includes/footer.php');
?>