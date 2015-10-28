<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Multi-Page Forms and PHP</title>
  <meta name="description" content="Multi-Page Forms and PHP">
  <meta name="author" content="ITA">

</head>

<body>
<h1>Hello</h1>

<form method="post" action="doorway.php">
	
	<p>What is your name?</p>
    <input type="text" name="name">
	<fieldset>
	<legend>What house do you belong to?</legend>
    <input type="radio" name="house" value="Thao">Thao<br />
	<input type="radio" name="house" value="Petersen">Petersen<br />
	<input type="radio" name="house" value="Pedracine">Pedracine<br />
	<input type="radio" name="house" value="Symonette">Symonette<br />
    <input type="submit" value="Go To Step 2">
	</fieldset>
</form>
</body>

</html>
