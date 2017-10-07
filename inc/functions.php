<?php
 
 function verify_query($result_set){
 	global $connection;

 	if (!$result_set){
 		die("database query failed: ".mysqli_error($connection));
 	}

 }

function check_req_fields($req_fields){
	$errors=array();
	//check requirerd fields
	foreach ($req_fields as $field) {
			if(empty($_POST[$field])){
			$errors[]= $field.' is required';
		}
		}
		return $errors;
}
 function check_max_len($max_len_fields){

 	$errors=array();
 	foreach ($max_len_fields as $field => $max_len) {
		if(strlen($_POST[$field]) > $max_len){
			$errors[]= $field.' must be less than '.$max_len. ' characters';
	}
}
return $errors; 
 }

function display_errors($errors){
	echo'<div class="errmsg">';
			echo '<b> There are errors<br> </b>';
				foreach ($errors as $error) { 
					$error= ucfirst(str_replace("_", " ", $error));
					echo $error.'<br>';
				}
			echo '</div>';

}
?>