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

		<h1>Welcome to the admin dashboard</h1>
		<br>
		<h2>Overview summary</h2>
		<br>
		<table class="table table-boardered">
            <thead>
                <tr>
                    <th>Total Users</th>
                    <th>Total Orders</th>
                    <th>Total Income</th>
                    <th>Pending Payments</th>
                    <th>Out of stock</th>
                    <th>Total Products</th>
                    <th>Total Reviews</th>
					<th>Average Rating</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $mysql = "SELECT
    -- Total number of users
    (SELECT COUNT(*) FROM Users) AS TotalUsers,

    -- Total number of orders
    (SELECT COUNT(*) FROM `Orders`) AS TotalOrders,

    -- Total sales income (only paid payments)
    (SELECT IFNULL(SUM(Amount), 0) FROM Payment WHERE PaymentStatus = 'Paid') AS TotalIncome,

    -- Number of orders with pending/unpaid status
    (SELECT COUNT(*) FROM Payment WHERE PaymentStatus IN ('Pending', 'Unpaid')) AS PendingPayments,

    -- Number of products that are out of stock
    (SELECT COUNT(*) FROM Product WHERE StockQuantity = 0) AS OutOfStockProducts,

    -- Number of products in the catalog
    (SELECT COUNT(*) FROM Product) AS TotalProducts,

    -- Total number of reviews
    (SELECT COUNT(*) FROM Review) AS TotalReviews,

    -- Average rating across all reviews
    (SELECT ROUND(AVG(Rating), 2) FROM Review) AS AverageRating
;";
                $result = mysqli_query($con, $mysql);
                while ($row = mysqli_fetch_array($result))
                {
                    ?>
                    <tr>
                        <td><?php echo $row["TotalUsers"]?></td>
                        <td><?php echo $row["TotalOrders"]?></td>
                        <td><?php echo $row["TotalIncome"]?></td>
                        <td><?php echo $row["PendingPayments"]?></td>
                        <td><?php echo $row["OutOfStockProducts"]?></td>
                        <td><?php echo $row["TotalProducts"]?></td>
						<td><?php echo $row["TotalReviews"]?></td>
						<td><?php echo $row["AverageRating"]?></td>
                    </tr>
                <?php
                }

                ?>
            </tbody>
        </table>
		<br>

		<a href="prodCRUD.php" class="btn btn-primary">Product Management</a>
		<a href="userCRUD.php" class="btn btn-primary">Users Management</a>
		<a href="cartCRUD.php" class="btn btn-primary">Carts Management</a>
	</div>
</body>
</html>
