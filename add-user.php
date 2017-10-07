<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>

<?php

	//checking that user logged into the system
	if (!isset($_SESSION['user_id'])){
		header('Location: index.php');
	}

	$errors = array();

	$first_name='';
	$last_name='';
	$email= '';
	$password= ''; 

	if (isset($_POST['submit'])){

		$first_name= $_POST['first_name'];
		$last_name= $_POST['last_name'];
		$email= $_POST['email'];
		$password= $_POST['password'];
		//checking required fields

		$req_fields= array('first_name','last_name','email','password');
		$errors=array_merge($errors,check_req_fields($req_fields));  
		
  
	//checking max length
	$max_length_fields = array('first_name' =>10,'last_name' => 10,'email' => 100,'password' => 40);
$errors=array_merge($errors,check_max_len($max_length_fields));
	

//checking email is already exist

$email = mysqli_real_escape_string($connection,$_POST['email']);
$query ="SELECT * FROM user WHERE email='{$email}' LIMIT 1";
$result_set=mysqli_query($connection,$query);

if($result_set){
	if (mysqli_num_rows($result_set)==1){
		$errors[]='email already exists';	}
}

if(empty($errors)){
	//adding new records

	$first_name = mysqli_real_escape_string($connection,$_POST['first_name']);
	$last_name = mysqli_real_escape_string($connection,$_POST['last_name']);
	$password = mysqli_real_escape_string($connection,$_POST['password']);
	//email already sanitized
	$hashed_password = sha1($password);

	$query= "INSERT INTO user (first_name,last_name,email,password,is_deleted)VALUES('{$first_name}','{$last_name}','{$email}','{$hashed_password}',0)";

	$result = mysqli_query($connection,$query);

	if($result){
		//query success redirect to users.php
		header('Location:users.php?user_added=true ');
	}else{
		$errors[]='Failed to addd new record';
	}


}
	  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>users</title>
	<link rel="stylesheet" type="text/css" href="css/main.css ">
</head>
<body>
	<header>
<div class="appname">user management system</div>
<div class="loggedin">Welcome <?php echo $_SESSION['first_name']; ?>!<a href="  logout.php">Logout</a></div><br>

	</header>

	<main>
	<h1 align="left">Add New User<span><a href="users.php">< back to user list</a></span></h1>

	<?php

	if(!empty($errors)){
		display_errors($errors);
	}

	?>

	

	<form action="add-user.php" method="post" class="userform">

	<p>
		<label for="">First Name:</label>
		<input type="text" name="first_name" <?php echo 'value="'.$first_name.'"';    ?>>
	</p>

	<p>
		<label for="">Last Name:</label>
		<input type="text" name="last_name" <?php echo 'value="'.$last_name.'"';    ?>>
	</p>

	<p>
		<label for="">Email:</label>
		<input type="email" name="email" <?php echo 'value="'.$email.'"';    ?>>
	</p>

	<p>
		<label for="">New password:</label>
		<input type="password" name="password" >
	</p>

	<p>  
		<label for="">&nbsp:</label>
		<button type="submit" name="submit">Save</button>	
	</p>
	</form>
	
	</main>
</body>
</html>