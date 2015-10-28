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
 
//let's start the session
session_start();
 
//finally, let's store our posted values in the session variables
$_SESSION['name'] = $_POST['name'];
$_SESSION['house'] = $_POST['house'];
 
 echo '<h1>Interesting, ' . $_SESSION['name'] . '... Prove It</h1>' ;
 
?>

<form method="post" action="welcome.php">
	
	<p>What is the password?</p>
    <input type="password" name="psw">
	<p>What is the name of your founder?</p>
    <input type="password" name="founder">
    <input type="submit" value="I'm not lyin">

</form>

</body>
</html>