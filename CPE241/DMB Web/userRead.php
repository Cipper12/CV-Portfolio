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
	<title>Product Details</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        .book-details
        {
            background: #f5f5f5;
            padding: 50px;
        }
    </style>

</head>
<body>
    <a href="logout.php">Logout</a>
	<br>
	Hello, <?php echo $user_data['Name']; ?>
    <br>
    <div class="container">
        <header class="d-flex justify-content-between my-4">
			<h1>User Details</h1>
			<div>
				<a href="userCRUD.php" class="btn btn-primary">Back</a>
			</div>
		</header>
        <div class="book-details p-5 my-4">
            <?php
            include("connection.php");
            $userID = $_GET["UserID"];
                if($userID)
                {
                    $mysql = "SELECT * FROM Users WHERE UserID = $userID";
                    $result = mysqli_query($con, $mysql);
                    while($row = mysqli_fetch_array($result))
                    {
                    ?>
                    <h3>User ID</h3>
                    <p><?php echo $row["UserID"]?></p>
                    <h3>Email</h3>
                    <p><?php echo $row["Email"]?></p>
                    <h3>Name</h3>
                    <p><?php echo $row["Name"]?></p>
                    <h3>Phone number</h3>
                    <p><?php echo $row["Phone"]?></p>
                    <h3>Address</h3>
                    <p><?php echo $row["Address"]?></p>
                    <h3>Role ID</h3>
                    <p><?php echo $row["RoleID"]?></p>
                    <h3>User Status</h3>
                    <p><?php echo $row["UStatus"]?></p>
                    <?php
                    }
                }
            ?>
        </div>
</body>
</html>