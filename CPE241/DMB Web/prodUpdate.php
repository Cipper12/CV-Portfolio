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
    <title>Edit Product</title>
</head>
<body>
    <a href="logout.php">Logout</a>
	<br>
	Hello, <?php echo $user_data['Name']; ?>
    <br>
    <div class="container">
        <header class="d-flex justify-content-between my-4">
			<h1>Edit Product</h1>
			<div>
				<a href="prodCRUD.php" class="btn btn-primary">Back</a>
			</div>
		</header>
    <?php
            $productID = $_GET["ProductID"];
            if($productID)
            {
                $query = "SELECT * FROM Product WHERE ProductID = $productID";
                $result = mysqli_query($con, $query);
                $row = mysqli_fetch_array($result);
            }
    ?>
    <form action="process.php" method="post">
			<div class="form-element my-4">
				<h3>Product ID: <?php echo $row["ProductID"]?> </h3>
			</div>
			<div class="form-element my-4">
				<h3>Category ID: </h3>
				<input type="text" class="form-control" name="CategoryID" value="<?php echo $row["CategoryID"]?>">
			</div>
			<div class="form-element my-4">
				<h3>Product Name: </h3>
				<input type="text" class="form-control" name="ProductName" value="<?php echo $row["ProductName"]?>">
			</div>
			<div class="form-element my-4">
				<h3>Price: </h3>
				<input type="text" class="form-control" name="Price" value="<?php echo $row["Price"]?>">
			</div>
			<div class="form-element my-4">
				<h3>Discount: </h3>
				<input type="text" class="form-control" name="Discount" value="<?php echo $row["Discount"]?>">
			</div>
			<div class="form-element my-4">
				<h3>Description: </h3>
				<input type="text" class="form-control" name="Description" value="<?php echo $row["Description"]?>">
			</div>
			<div class="form-element my-4">
				<h3>Stock Quantity: </h3>
				<input type="text" class="form-control" name="StockQuantity" value="<?php echo $row["StockQuantity"]?>">
			</div>
            <input type="hidden" name="ProductID" value="<?php echo $row["ProductID"]?>">
			<div class="form-element">
				<input type="submit" class="btn btn-success" name="Edit" values="Edit Product">
			</div>
		</form>
	</div>
</body>
</html>
