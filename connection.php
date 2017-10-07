<?php 				//This is the connection to the databse


	$dbhost=  'localhost';
	$dbuser= 'root';
	$dbpass= '';
	$dbname= 'userdb';

	$connection= mysqli_connect('localhost','root','','userdb');

	//checking the connection

	if (mysqli_connect_errno()){
		die('databse connection failed'. mysqli_connect_error());
	}
	
	?>
