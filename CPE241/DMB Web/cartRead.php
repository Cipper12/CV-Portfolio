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
	<title>Cart Details</title>
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
			<h1>Cart Details</h1>
			<div>
				<a href="cartCRUD.php" class="btn btn-primary">Back</a>
			</div>
		</header>
        <div class="book-details p-5 my-4">
            <?php
            $cartID = $_GET["CartID"];
            $userID = $_GET["UserID"];
                if($userID)
                {
                    $mysql = "SELECT * FROM CartItem CI JOIN Cart C ON CI.CartID = C.CartID WHERE UserID = $userID";
                    $result = mysqli_query($con, $mysql);
                    while($row = mysqli_fetch_array($result))
                    {
                    ?>
                    <h3>User ID</h3>
                    <p><?php echo $row["UserID"]?></p>
                    <h3>Cart ID</h3>
                    <p><?php echo $row["CartID"]?></p>
                    <h3>Created Date</h3>
                    <p><?php echo $row["CreatedDate"]?></p>
                    <h3>Cart Item ID</h3>
                    <p><?php echo $row["CartItemID"]?></p>
                    <h3>Product ID</h3>
                    <p><?php echo $row["ProductID"]?></p>
                    <h3>Quantity</h3>
                    <p><?php echo $row["Quantity"]?></p>
                    <?php
                    }
                }
            ?>
        </div>
</body>
</html>