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
			<h1>Product Details</h1>
			<div>
				<a href="customerIndex.php" class="btn btn-primary">Back</a>
			</div>
		</header>
        <div class="book-details p-5 my-4">
            <?php
            include("connection.php");
            $productID = $_GET["ProductID"];
                if($productID)
                {
                    $mysql = "SELECT * FROM Product WHERE ProductID = $productID";
                    $result = mysqli_query($con, $mysql);
                    while($row = mysqli_fetch_array($result))
                    {
                    ?>
                    <h3>Product Name</h3>
                    <p><?php echo $row["ProductName"]?></p>
                    <h3>Description</h3>
                    <p><?php echo $row["Description"]?></p>
                    <h3>Price</h3>
                    <p><?php echo $row["Price"]?></p>
                    <h3>Discount</h3>
                    <p><?php echo $row["Discount"]?></p>
                    <h3>Product ID</h3>
                    <p><?php echo $row["ProductID"]?></p>
                    <h3>Category ID</h3>
                    <p><?php echo $row["CategoryID"]?></p>
                    <?php
                    }
                }
            ?>
        </div>
</body>
</html>