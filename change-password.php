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
		$password=$_POST['password'];
		
		//$password= $_POST['password'];
		//checking required fields

		$req_fields= array('user_id','password');
		$errors=array_merge($errors,check_req_fields($req_fields));  
		
  
	//checking max length
	$max_length_fields = array('password' => 40);
$errors=array_merge($errors,check_max_len($max_length_fields));
	

//checking email is already exist
//removed

if(empty($errors)){
	//adding new records

	$password = mysqli_real_escape_string($connection,$_POST['password']);
	$hashed_password = sha1($password);
	
	//$password = mysqli_real_escape_string($connection,$_POST['password']);
	//email already sanitized
	//

	$query= "UPDATE user SET password='{$hashed_password}' WHERE id={$user_id} LIMIT 1 ";


	//query goes here
	//$query= "INSERT INTO user (first_name,last_name,email,password,is_deleted)VALUES('{$first_name}','{$last_name}','{$email}','{$hashed_password}',0)";

	$result = mysqli_query($connection,$query);

	if($result){
		//query success redirect to users.php
		header('Location:users.php?user_modify=true ');
	}else{
		$errors[]='Failed to update the password';
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
	<h1 align="left">Change Password<span><a href="users.php">< back to user list</a></span></h1>

	<?php

	if(!empty($errors)){
		display_errors($errors);
	}

	?>

	

	<form action="change-password.php" method="post" class="userform">

	<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
	<p>
		<label for="">First Name:</label>
		<input type="text" name="first_name" <?php echo 'value="'.$first_name.'"';    ?> disabled>
	</p>

	<p>
		<label for="">Last Name:</label>
		<input type="text" name="last_name" <?php echo 'value="'.$last_name.'"';    ?> disabled>
	</p>

	<p>
		<label for="">Email:</label>
		<input type="email" name="email" <?php echo 'value="'.$email.'"';    ?> disabled>
	</p>

	<p>
		<label for="">New Password:</label>
		<input type="password" name="password" id="password">
	</p>

	<p>
		<label for="">Show Password:</label>
		<input type="checkbox" name="showpassword" id="showpassword" style="width:25px; height:20px">
	</p>

	<p>  
		<label for="">&nbsp:</label>
		<button type="submit" name="submit">Update Password</button>	
	</p>
	</form>
	
	</main>
	<script src="js/jquery.js"></script>
	<script>
	$(document).ready(function(){
		$('#showpassword').click(function(){
			if ($('#showpassword').is(':checked')){
				$('#password').attr('type','text');
		}else{
			$('#password').attr('type','password');
		}
		});
	});

	</script>
</body>
</html>