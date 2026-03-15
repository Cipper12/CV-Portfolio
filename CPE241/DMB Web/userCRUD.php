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
			<h1>User Management</h1>
			<div>
				<a href="index.php" class="btn btn-primary">Back</a>
			</div>
		</header>
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
        ?> 
        <table class="table table-boardered">
            <thead>
                <tr>
                    <th>UserID</th>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>RoleID</th>
                    <th>UStatus</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $mysql = "SELECT * FROM Users";
                if($user_data["RoleID"] == 3)
                {
                    $userID = $user_data["UserID"];
                    $mysql = "SELECT * FROM Users WHERE UserID = '$userID'";
                }
                $result = mysqli_query($con, $mysql);
                while ($row = mysqli_fetch_array($result))
                {
                    ?>
                    <tr>
                        <td><?php echo $row["UserID"]?></td>
                        <td><?php echo $row ["Email"]?></td>
                        <td><?php echo $row["Name"]?></td>
                        <td><?php echo $row["Phone"]?></td>
                        <td><?php echo $row["Address"]?></td>
                        <td><?php echo $row["RoleID"]?></td>
                        <td><?php echo $row["UStatus"]?></td>
                        <td>
                            <?php if($user_data["RoleID"] != 3)
                            {
                                ?>
                                <a href="userRead.php?UserID=<?php echo $row["UserID"]?>" class="btn btn-info">Read more</a>
                                <a href="userUpdate.php?UserID=<?php echo $row["UserID"]?>" class="btn btn-warning">Edit</a>
                                <a href="userDelete.php?UserID=<?php echo $row["UserID"]?>" class="btn btn-danger">Delete</a>
                                <?php
                            }else
                            {
                                if($user_data["UserID"] == $row["UserID"])
                                {
                                    ?>
                                    <a href="userRead.php?UserID=<?php echo $row["UserID"]?>" class="btn btn-info">Read more</a>
                                    <a href="userUpdate.php?UserID=<?php echo $row["UserID"]?>" class="btn btn-warning">Edit</a>
                                    <a href="userDelete.php?UserID=<?php echo $row["UserID"]?>" class="btn btn-danger">Delete</a>
                                    <?php
                                }else
                                {
                                    ?>
                                    <a href="" class="btn btn-outline-secondary" disabled>Read more</a>
                                    <a href="" class="btn btn-outline-secondary" disabled>Edit</a>
                                    <a href="" class="btn btn-outline-secondary" disabled>Delete</a>
                                    <?php
                                }
                                    ?>
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