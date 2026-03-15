<?php
session_start();

	include("connection.php");
	include("functions.php");

	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		//something was posted
		$email = $_POST['Email'];
		$password = $_POST['Password'];

		echo "Made it here";

		if(!empty($email) && !empty($password))
		{
			//read from database
			$query = "select * from Users where Email = '$email' limit 1";
			$result = mysqli_query($con, $query);
			
			if($result)
			{
				if($result && mysqli_num_rows($result) > 0)
				{
					$user_data = mysqli_fetch_assoc($result);
					
					if($user_data['Password'] === $password)
					{
						$_SESSION['UserID'] = $user_data['UserID'];
						header("Location: customerIndex.php");
						die;
					}
				}
			}
			echo "wrong username or password";

		}else
		{
		echo "Please enter valid information!";
		}
	}

?>


<!DOCTYPE html>
<html>
<head>
	<title>Customer Login</title>
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

		background-color: #f5f5f5;
		margin: auto;
		width: 300px;
		padding: 20px;
	}
	</style>
	<div id="box">
		<form method="post">
		<div style="font-size: 20px;margin: 10px;">Customer Login</div>
			Email
			<input id="text" type ="text" name="Email"><br><br>
			Password
			<input id="text" type ="password" name="Password"><br><br>
			
			<input id="button" type="submit" value="Login"><br><br>
			
			<a href="signup.php">Click to sign up</a><br><br>
		</form>
	</div>
</body>
</html>
