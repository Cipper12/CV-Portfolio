<?php
session_start();
include("connection.php");
include("functions.php");
$user_data = check_login($con);
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Create Product</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

	<a href="logout.php">Logout</a>
	<br>
	Hello, <?php echo $user_data['Name']; ?>
	<br>
	<div class="container">
		<header class="d-flex justify-content-between my-4">
			<h1>Add new product</h1>
			<div>
				<a href="prodCRUD.php" class="btn btn-primary">Back</a>
			</div>
		</header>
		<form action="process.php" method="post">
			<div class="form-element my-4">
				<input type="text" class="form-control" name="ProductID" placeholder="Enter product ID: ">
			</div>
			<div class="form-element my-4">
				<input type="text" class="form-control" name="CategoryID" placeholder="Enter category ID: ">
			</div>
			<div class="form-element my-4">
				<input type="text" class="form-control" name="ProductName" placeholder="Enter product name: ">
			</div>
			<div class="form-element my-4">
				<input type="text" class="form-control" name="Price" placeholder="Enter product price: ">
			</div>
			<div class="form-element my-4">
				<input type="text" class="form-control" name="Discount" placeholder="Enter product discount: ">
			</div>
			<div class="form-element my-4">
				<input type="text" class="form-control" name="Description" placeholder="Enter product description: ">
			</div>
			<div class="form-element my-4">
				<input type="text" class="form-control" name="StockQuantity" placeholder="Enter product quantity: ">
			</div>
			<div class="form-element">
				<input type="submit" class="btn btn-success" name="Create" values="Add product">
			</div>
		</form>
	</div>
</body>
</html>
