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

//get the session data that we'll compare against the database
 $name = $_SESSION['name'];
 $house = $_SESSION['house'];
 
 //get the data from database
 $statement = $conn->prepare(
							"SELECT user.user_firstname, house.house_name
								FROM user
								INNER JOIN house ON house.house_id = user.house_id
								WHERE user.user_firstname =  ?
								AND house.house_name =  ?"
							);
 $statement->execute(array($name, $house));
 $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
 
 //check whether our session data and database data match
 $numResults = count($rows);
 $i = 0;
 $isUserInHouse = false;
 $isHouseCorrect = false;
 while( $i < $numResults && ( !$isUserInHouse || !$isHouseCorrect ) ) {
	 
	 foreach( $rows[$i] as $key => $value ){
		if( !$isUserInHouse && ($key === "user_firstname") ) {
			$isUserInHouse = $name === $value;
		}
		
		if( !$isHouseCorrect && ($key === "house_name") ) {
			$isHouseCorrect = $house === $value;
		}
	 }
	 
	 $i++;
 }
//finally, let's store our posted values in the session variables
$_SESSION['psw'] = $_POST['psw'];
$_SESSION['founder'] = $_POST['founder'];
 
 $housePW = array(
				"Thao" => "Loyalty",
				"Petersen" => "Innovation",
				"Pedracine" => "Ambition",
				"Symonette" => "Vision"
				);
				
$houseFounder = array(
				"Thao" => "Chou",
				"Petersen" => "Drew",
				"Pedracine" => "Roslynn",
				"Symonette" => "Hazel"
				);

$houseInfo = array(
				"Thao" => "Thao House is named after Chou Thao. Chou is an ITA Graduate from the class of 2004 - the very first class of ITA! Chou was also an office assistant and worked as a tech instructor. One thing we are certain about Chou is that he was always LOYAL to the ITA program, and he even still helps out today with translation services for ITA interviews!",
				"Petersen" => "Petersen House is named after Drew Petersen. Drew is responsible for creating the Panda web page we use at ITA. He was always INNOVATIVE! He actually loved PHP code, and would love this little PHP project. Drew currently works at Spotify, but before that, just a couple jobs ago, he worked here at ITA as a tech instructor!",
				"Pedracine" => "Pedracine House is named after Roslynn Pedracine. Roslynn is an ITA Graduate from the class of 2004 - the very first class of ITA! Roslynn worked as a tech instructor. One thing we are certain about Roslynn is that she is AMBITIOUS! She currently serves on the ITA Advisory Board and works in D.C. in the Federal HUD program!",
				"Symonette" => "Symonette House is named after Hazel Symonette. Hazel was one of the co-founders of ITA! She also has served on the ITA Advisory Board since the very beginning. Hazel is very inspirational and a major role model for anyone who is lucky enough to work with her. We owe her a big gratitude for her service to ITA and her VISION around social change!"				
				);

$isCorrectPW = $_SESSION['psw'] === $housePW[ $_SESSION['house'] ];
$isCorrectFounder = $_SESSION['founder'] === $houseFounder[ $_SESSION['house'] ];

//check the user is in the house they said they are, and that they know the correct information
if( $isHouseCorrect && $isUserInHouse && $isCorrectPW && $isCorrectFounder ) {
	echo '<h1>Welcome to ' . $_SESSION['house'] . ' House, ' . $_SESSION['name'] . '</h1>';
	echo '<p>' . $houseInfo[ $_SESSION['house'] ] . '</p>';
}
else {
	echo '<h2> WHY YOU LYING!?</h2>';
}

echo '<p><a href="index.php">Go back from whence you came!</a><p>';
					
?>

</body>
</html>