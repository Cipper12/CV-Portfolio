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
    <title>Product List</title>
</head>
<body>
    <a href="logout.php">Logout</a>
	<br>
	Hello, <?php echo $user_data['Name']; ?>
    <br>
    <div class="container">
        <header class="d-flex justify-content-between my-4">
			<h1>Product List</h1>
			<div>
                <a href="index.php" class="btn btn-primary">Back</a>
                <?php if($user_data["RoleID"] != 3)
                            {
                                ?>
                                <a href="prodCreate.php" class="btn btn-primary">Add new product</a>
                                <?php
                            }else
                            {
                                ?>
                                <a href="" class="btn btn-outline-secondary" disabled>Add new product</a>
                                <?php
                            }
                            ?>
				
			</div>
		</header>
        <?php
        if(isset($_SESSION["Create"]))
        {
            ?>
            <div class="alert alert-success">
                <?php 
                echo $_SESSION["Create"];
                unset($_SESSION["Create"]);
                ?>
            </div>
            <?php
        }
        ?>
        <?php
        if(isset($_SESSION["Edit"]))
        {
            ?>
            <div class="alert alert-success">
                <?php 
                echo $_SESSION["Edit"];
                unset($_SESSION["Edit"]);
                ?>
            </div>
            <?php
        }
        ?> 
        <?php
        if(isset($_SESSION["Delete"]))
        {
            ?>
            <div class="alert alert-success">
                <?php 
                echo $_SESSION["Delete"];
                unset($_SESSION["Delete"]);
                ?>
            </div>
            <?php
        }
        if(isset($_SESSION["No permission"]))
        {
            ?>
            <div class="alert alert-danger">
                <?php
                echo $_SESSION["No permission"];
                unset($_SESSION["No permission"]);
                ?>
            </div>
            <?php
        }
        ?> 
        <table class="table table-boardered">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Category ID</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include("connection.php");
                $mysql = "SELECT * FROM Product";
                $result = mysqli_query($con, $mysql);
                while ($row = mysqli_fetch_array($result))
                {
                    ?>
                    <tr>
                        <td><?php echo $row["ProductID"]?></td>
                        <td><?php echo $row ["CategoryID"]?></td>
                        <td><?php echo $row["ProductName"]?></td>
                        <td><?php echo $row["Price"]?></td>
                        <td><?php echo $row["Discount"]?></td>
                        <td><?php echo $row["StockQuantity"]?></td>
                        <td>
                            <a href="prodRead.php?ProductID=<?php echo $row["ProductID"]?>" class="btn btn-info">Read more</a>
                            <?php if($user_data["RoleID"] != 3)
                            {
                                ?>
                                <a href="prodUpdate.php?ProductID=<?php echo $row["ProductID"]?>" class="btn btn-warning">Edit</a>
                                <a href="prodDelete.php?ProductID=<?php echo $row["ProductID"]?>" class="btn btn-danger">Delete</a>
                                <?php
                            }else
                            {
                                ?>
                                <a href="" class="btn btn-outline-secondary" disabled>Edit</a>
                                <a href="" class="btn btn-outline-secondary" disabled>Delete</a>
                                <?php
                            }
                            ?>
                            
                            
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