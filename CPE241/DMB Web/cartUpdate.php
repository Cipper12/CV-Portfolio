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
    <title>Edit Cart</title>
</head>
<body>
    <a href="logout.php">Logout</a>
	<br>
	Hello, <?php echo $user_data['Name']; ?>
    <br>
    <div class="container">
        <header class="d-flex justify-content-between my-4">
			<h1>Edit Cart</h1>
			<div>
				<a href="cartCRUD.php" class="btn btn-primary">Back</a>
			</div>
		</header>
    <?php
            $cartID = $_GET["CartID"];
            $userID = $_GET["UserID"];
            if($userID)
            {
                $query = "SELECT * FROM CartItem CI JOIN Cart C ON CI.CartID = C.CartID WHERE UserID = '$userID'";
                $result = mysqli_query($con, $query);
                $row = mysqli_fetch_array($result);
            }
    ?>
    <form action="cartProcess.php" method="post">
			<div class="form-element my-4">
				<h3>User ID: <?php echo $row["UserID"]?> </h3>
			</div>
			<div class="form-element my-4">
				<h3>Cart ID: </h3>
				<input type="text" class="form-control" name="CartID" value="<?php echo $row["CartID"]?>">
			</div>
			<div class="form-element my-4">
				<h3>Created Date: </h3>
				<input type="text" class="form-control" name="CreatedDate" value="<?php echo $row["CreatedDate"]?>">
			</div>
			<div class="form-element my-4">
				<h3>Cart Item ID: </h3>
				<input type="text" class="form-control" name="CartItemID" value="<?php echo $row["CartItemID"]?>">
			</div>
			<div class="form-element my-4">
				<h3>Product ID: </h3>
				<input type="text" class="form-control" name="ProductID" value="<?php echo $row["ProductID"]?>">
			</div>
			<div class="form-element my-4">
				<h3>Quantity:  </h3>
				<input type="text" class="form-control" name="Quantity" value="<?php echo $row["Quantity"]?>">
			</div>
            <input type="hidden" name="UserID" value="<?php echo $row["UserID"]?>">
			<div class="form-element">
				<input type="submit" class="btn btn-success" name="Edit" values="Edit Cart">
			</div>
		</form>
	</div>
</body>
</html>
