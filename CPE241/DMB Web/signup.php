<?php
session_start();

	include("connection.php");
	include("functions.php");

	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		//something was posted
		$user_name = $_POST['Name'];
		$password = $_POST['Password'];
		$email = $_POST['Email'];
		$role = $_POST['Role'];
		if(!empty($user_name) && !empty($password) && !empty($email))
		{
			//save to database
			// $user_id = random_num(20); Not needed for our database
			$query = "INSERT INTO Users (Name, Email, Password, RoleID) VALUES ('$user_name', '$email', '$password', '$role')";
			
			mysqli_query($con, $query);
			if($role == 3)
			{
				header("Location: customerLogin.php");
			}
			header("Location: login.php");
			die;
		}else
		{
			echo "Please enter valid information!";
		}
	}

?>


<!DOCTYPE html>
<html>
<head>
	<title>Sign Up</title>
</head>
<body>

	<style type="text/css">
	#text{

		height: 25px;
		border-radius: 5px;
		padding: 4px;
		border: solid thin #aaa;
		width: 100%;
	}

	#button{

		padding: 10px;
		width: 100px;
		color: white;
		background-color: lightblue;
		border: none;
	}

	#box{

		background-color: grey;
		margin: auto;
		width: 300px;
		padding: 20px;
	}
	</style>
	<div id="box">
		<form method="post">
			<div style="font-size: 20px;margin: 10px;color: white;">Sign up</div>
			Email
			<input id="text" type ="text" name="Email"><br><br>
			Name
			<input id="text" type ="text" name="Name"><br><br>
			Password
			<input id="text" type ="password" name="Password"><br><br>
			RoleID (0-3)
			<input id="text" type ="text" name="Role"><br><br>
			<input id="button" type="submit" value="Sign up"><br><br>
			<a href="login.php">Click to Admin Login</a><br><br>
			<a href="customerLogin.php">Click to Customer Login</a>
		</form>
	</div>
</body>
</html>
