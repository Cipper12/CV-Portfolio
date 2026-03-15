<?php
session_start();
include("connection.php");
include("functions.php");
$user_data = check_login($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Edit User</title>
</head>
<body>
    <a href="logout.php">Logout</a>
	<br>
	Hello, <?php echo $user_data['Name']; ?>
    <br>
    <div class="container">
        <header class="d-flex justify-content-between my-4">
			<h1>Edit User</h1>
			<div>
				<a href="userCRUD.php" class="btn btn-primary">Back</a>
			</div>
		</header>
    <?php
            $userID = $_GET["UserID"];
            if($userID)
            {
                $query = "SELECT * FROM Users WHERE UserID = $userID";
                $result = mysqli_query($con, $query);
                $row = mysqli_fetch_array($result);
            }
    ?>
    <form action="userProcess.php" method="post">
			<div class="form-element my-4">
				<h3>User ID: <?php echo $row["UserID"]?> </h3>
			</div>
			<div class="form-element my-4">
				<h3>Emai: </h3>
				<input type="text" class="form-control" name="Email" value="<?php echo $row["Email"]?>">
			</div>
			<div class="form-element my-4">
				<h3>Name: </h3>
				<input type="text" class="form-control" name="Name" value="<?php echo $row["Name"]?>">
			</div>
			<div class="form-element my-4">
				<h3>Phone number: </h3>
				<input type="text" class="form-control" name="Phone" value="<?php echo $row["Phone"]?>">
			</div>
			<div class="form-element my-4">
				<h3>Address: </h3>
				<input type="text" class="form-control" name="Address" value="<?php echo $row["Address"]?>">
			</div>
			<div class="form-element my-4">
				<h3>Role ID: </h3>
				<input type="text" class="form-control" name="RoleID" value="<?php echo $row["RoleID"]?>">
			</div>
			<div class="form-element my-4">
				<h3>User Status: </h3>
				<input type="text" class="form-control" name="UStatus" value="<?php echo $row["UStatus"]?>">
			</div>
            <input type="hidden" name="UserID" value="<?php echo $row["UserID"]?>">
			<div class="form-element">
				<input type="submit" class="btn btn-success" name="Edit" values="Edit User">
			</div>
		</form>
	</div>
</body>
</html>
