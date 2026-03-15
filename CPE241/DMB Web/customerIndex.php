<?php
    session_start();
	include("connection.php");
	include("functions.php");
	$user_data = check_login($con);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Shopping site project</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
	<div class="container">
		<header class="d-flex justify-content-between my-4">
			<p>Hello, <?php echo $user_data['Name']; ?></p>
			<div>
				<a href="logout.php" class="btn btn-primary">Logout</a>
			</div>
		</header>

		<h1>Welcome to the customer index</h1>
		<br>
		<h2>Product Available</h2>
		<br>
		<table class="table table-boardered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $mysql = "SELECT * FROM Product";
                $result = mysqli_query($con, $mysql);
                while ($row = mysqli_fetch_array($result))
                {
                ?>
                    <tr>
                        <td><?php echo $row["ProductName"]?></td>
                        <td><?php echo $row["Price"]?></td>
                        <td><?php echo $row["StockQuantity"]?></td>
                        <td>
                            <a href="customerProdRead.php?ProductID=<?php echo $row["ProductID"]?>" class="btn btn-primary">Read more</a>
                            <a href="cartCreate.php?ProductID=<?php echo $row["ProductID"]?>" class="btn btn-success">Add 1 to cart</a>
                        </td>
                    </tr>
                <?php
                }

                ?>
            </tbody>
        </table>
	</div>
</body>
</html>