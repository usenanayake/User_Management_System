<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>

<?php

	//checking that user logged into the system
	if (!isset($_SESSION['user_id'])){
		header('Location: index.php');
	}

	$errors = array();

	$user_id='';
	$first_name='';
	$last_name='';
	$email= '';
	$password= ''; 

	if(isset($_GET['user_id'])){
		//getting user information
		$user_id=mysqli_real_escape_string($connection,$_GET['user_id']);
		$query= "SELECT * FROM user WHERE id = {$user_id} LIMIT 1";
		$result_set = mysqli_query($connection,$query);

		if($result_set){
			if(mysqli_num_rows($result_set)==1){
				//user found
				$result = mysqli_fetch_assoc($result_set);

			$first_name=$result['first_name'];
			$last_name=$result['last_name'];
			$email= $result['email'];

			}else{
				//user not found
				header('Location:users.php?user_not_found ');
			}
		}else{
			//query unsuccessfull
			header('Location:users.php?err=query_failed');
		}
}
	if (isset($_POST['submit'])){

		$user_id=$_POST['user_id'];
		$first_name= $_POST['first_name'];
		$last_name= $_POST['last_name'];
		$email= $_POST['email'];
		//$password= $_POST['password'];
		//checking required fields

		$req_fields= array('user_id','first_name','last_name','email');
		$errors=array_merge($errors,check_req_fields($req_fields));  
		
  
	//checking max length
	$max_length_fields = array('first_name' => 50, 'last_name' => 50,'email' => 100);
$errors=array_merge($errors,check_max_len($max_length_fields));
	

//checking email is already exist

$email = mysqli_real_escape_string($connection,$_POST['email']);
$query ="SELECT * FROM user WHERE email='{$email}' AND id !={$user_id} LIMIT 1"; 
$result_set=mysqli_query($connection,$query);

if($result_set){
	if (mysqli_num_rows($result_set)==1){
		$errors[]='email already exists';	}
}

if(empty($errors)){
	//adding new records

	$first_name = mysqli_real_escape_string($connection,$_POST['first_name']);
	$last_name = mysqli_real_escape_string($connection,$_POST['last_name']);
	//$password = mysqli_real_escape_string($connection,$_POST['password']);
	//email already sanitized
	//$hashed_password = sha1($password);

	$query= "UPDATE user SET first_name='{$first_name}', last_name='{$last_name}',
			email='{$email}' WHERE id={$user_id} LIMIT 1 ";


	//query goes here
	//$query= "INSERT INTO user (first_name,last_name,email,password,is_deleted)VALUES('{$first_name}','{$last_name}','{$email}','{$hashed_password}',0)";

	$result = mysqli_query($connection,$query);

	if($result){
		//query success redirect to users.php
		header('Location:users.php?user_modify=true ');
	}else{
		$errors[]='Failed to modify new record';
	}


}
	  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Change Password</title>
	<link rel="stylesheet" type="text/css" href="css/main.css ">
</head>
<body>
	<header>
<div class="appname">user management system</div>
<div class="loggedin">Welcome <?php echo $_SESSION['first_name']; ?>!<a href="  logout.php">Logout</a></div><br>

	</header>

	<main>
	<h1 align="left">View / Modify user<span><a href="users.php">< back to user list</a></span></h1>

	<?php

	if(!empty($errors)){
		display_errors($errors);
	}

	?>

	

	<form action="modify-user.php" method="post" class="userform">

	<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
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
		<label for="">Password:</label>
		<span>******</span>|<a href="change-password.php?user_id=<?php echo $user_id; ?>">Change Password</a>
	</p>

	<p>  
		<label for="">&nbsp:</label>
		<button type="submit" name="submit">Save</button>	
	</p>
	</form>
	
	</main>
</body>
</html>