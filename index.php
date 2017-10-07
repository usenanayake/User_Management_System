 <?php session_start(); ?>
 <?php require_once('inc/connection.php'); ?>
 <?php require_once('inc/functions.php'); ?>
<?php
//check for submission
 if (isset($_POST['submit'])){

 	$errors=array();

//check username passwod entered
 	if(!isset($_POST['email']) || strlen(trim($_POST['email']))<1){
 		$errors[]='username is missing / invalid';
 	}

 	if(!isset($_POST['password']) || strlen(trim($_POST['password']))<1){
 		$errors[]='password is missing / invalid';
 	}

//check for errors
if(empty($errors)){
//save user name password to variables
		$email = mysqli_real_escape_string($connection,$_POST['email']);
		$password = mysqli_real_escape_string($connection,$_POST['password']);
		$hashed_password = sha1($password);

//prepare database query
$query="SELECT * FROM user
WHERE email='{$email}'
AND password= '{$hashed_password}'
LIMIT 1";

$result_set = mysqli_query($connection,$query);
//var_dump($result_set);

verify_query($result_set);
	//query success
	if(mysqli_num_rows($result_set)==1){
		//valid user found
		$user= mysqli_fetch_assoc($result_set);
		//var_dump($user);
		$_SESSION['user_id']= $user['id'];
		$_SESSION['first_name']= $user['first_name'];

		//updating last login
		$query= "UPDATE user SET last_login= NOW()";
		$query .= "WHERE id ={$_SESSION['user_id']} LIMIT 1";

		$result_set= mysqli_query($connection,$query);

		verify_query($result_set);
			//die("database connection failed");
		
		//redirect users.php 
		header('location: users.php');
	}else{
		//username password invalid
		$errors[]='invalid username / password';
	}





//if not display error
} 
 }

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login user management system</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
	<div class="login">
  <form action="index.php" method="post">

  	<fieldset>
<legend> <h1>log in</h1></legend>
<?php
if (isset($errors) && !empty($errors)){
	echo '<p class="error">Invalid username / Password</p>';
}
?>
<?php
	if (isset($_GET['logout'])){
		echo '<p class="info">Logout sucessfully</p>';
	}

?>
		<label for="">username:</label>
		<input type ="text" name="email" id="" placeholder="email addr">
	</p>

	<p>
		<label for="">password:</label>
		<input type ="password" name="password" id="" placeholder="password">
	</p>
	<p>
		<button type="submit" name="submit">Log in</button>

  	</fieldset>
  </form>
	</div> <!-- login -->
</body>
</html>

<?php mysqli_close($connection); ?> 