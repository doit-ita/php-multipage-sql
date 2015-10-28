<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Multi-Page Forms and PHP</title>
  <meta name="description" content="Multi-Page Forms and PHP">
  <meta name="author" content="ITA">

</head>

<body>

<?php
 
 //connect to the database
 require './connect-db.php';
 
 
//let's start the session
session_start();

// This if statement checks to determine whether the registration form has been submitted 
    // If it has, then the registration code is run, otherwise the form is displayed 
    if(!empty($_POST)) 
    { 
		// Ensure that the user has entered a non-empty first name 
        if(empty($_POST['firstname'])) 
        { 
            // Note that die() is generally a terrible way of handling user errors 
            // like this.  It is much better to display the error with the form 
            // and allow the user to correct their mistake.  
            die("Please enter your first name."); 
        } 
		
		// Ensure that the user has selected a house 
        if(empty($_POST['house'])) 
        { 
            die("Please choose your house."); 
        } 
		
        // Ensure that the user has entered a non-empty username 
        if(empty($_POST['username'])) 
        { 
            die("Please enter a username."); 
        } 
         
        // Ensure that the user has entered a non-empty password 
        if(empty($_POST['password'])) 
        { 
            die("Please enter a password."); 
        } 
         
        
        // We will use this SQL query to see whether the username entered by the 
        // user is already in use.  A SELECT query is used to retrieve data from the database. 
        // :username is a special token, we will substitute a real value in its place when 
        // we execute the query. 
        $query = " 
            SELECT 
                username 
            FROM user 
            WHERE 
                username = :username 
        "; 
         
        // This contains the definitions for any special tokens that we place in 
        // our SQL query.  In this case, we are defining a value for the token 
        // :username.  It is possible to insert $_POST['username'] directly into 
        // your $query string; however doing so is very insecure and opens your 
        // code up to SQL injection exploits.  Using tokens prevents this. 
        // For more information on SQL injections, see Wikipedia: 
        // http://en.wikipedia.org/wiki/SQL_Injection 
        $query_params = array( 
            ':username' => $_POST['username'] 
        ); 
         
        try 
        { 
            // These two statements run the query against your database table. 
            $stmt = $conn->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) 
        { 
            // Note: On a production website, you should not output $ex->getMessage(). 
            // It may provide an attacker with helpful information about your code.  
            die("Failed to run query: " . $ex->getMessage()); 
        } 
         
        // The fetch() method returns an array representing the "next" row from 
        // the selected results, or false if there are no more rows to fetch. 
        $row = $stmt->fetch(); 
         
        // If a row was returned, then we know a matching username was found in 
        // the database already and we should not allow the user to continue. 
        if($row) 
        { 
            die("This username is already in use"); 
        } 
         
        // Now we check for the user account in the house 
        // to ensure that it hasn't registered already. 
        $query = " 
            SELECT 
                * 
            FROM user 
            WHERE 
                user_firstname = :firstname
			AND house_id = :houseid
			AND username IS NULL
			AND password IS NULL
        "; 
         
        $query_params = array( 
            ':firstname' => $_POST['firstname'],
			':houseid' => $_POST['house']
        ); 
         
        try 
        { 
            $stmt = $conn->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) 
        { 
            die("Failed to run query: " . $ex->getMessage()); 
        } 
         
        $row = $stmt->fetch(); 
         
        if(!$row) 
        { 
            die("User already registered"); 
        } 
         
        // An INSERT query is used to add new rows to a database table. 
        // Again, we are using special tokens (technically called parameters) to 
        // protect against SQL injection attacks. 
        $query = " 
            UPDATE user 
			SET
                username = :username, 
                password = :password 
            WHERE
				user_firstname = :firstname
			AND house_id = :houseid
			AND username IS NULL
			AND password IS NULL
        "; 
         
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
         
        // Here we prepare our tokens for insertion into the SQL query.  We do not 
        // store the original password; only the hashed version of it.  We do store 
        // the salt (in its plaintext form; this is not a security risk). 
        $query_params = array( 
            ':username' => $_POST['username'], 
            ':password' => $password, 
			':firstname' => $_POST['firstname'],
			':houseid' => $_POST['house']
        ); 
         
        try 
        { 
            // Execute the query to create the user 
            $stmt = $conn->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) 
        { 
            // Note: On a production website, you should not output $ex->getMessage(). 
            // It may provide an attacker with helpful information about your code.  
            die("Failed to run query: " . $ex->getMessage()); 
        } 
         
        // This redirects the user back to the login page after they register 
        header("Location: login.php"); 
         
        // Calling die or exit after performing a redirect using the header function 
        // is critical.  The rest of your PHP script will continue to execute and 
        // will be sent to the user if you do not die or exit. 
        die("Redirecting to login.php"); 
    } 
     
?> 
<h1>Register</h1> 
<form action="register.php" method="post">
	<p>What is your first name?</p>
    <input type="text" name="firstname">
	<fieldset>
	<legend>What house do you belong to?</legend>
    <input type="radio" name="house" value="4">Thao<br />
	<input type="radio" name="house" value="2">Petersen<br />
	<input type="radio" name="house" value="1" checked>Pedracine<br />
	<input type="radio" name="house" value="3">Symonette<br />
	</fieldset>
	<br />
    Username:<br /> 
    <input type="text" name="username" value="" /> 
    <br /><br /> 
    Password:<br /> 
    <input type="password" name="password" value="" /> 
    <br /><br /> 
    <input type="submit" value="Register" /> 
</form>

</body>
</html>