<?php

include("connection.php");

if(isset($_POST["Edit"]))
{
    $userID = $_POST["UserID"];
    $cartID = $_POST["CartID"];
    $createdDate = $_POST["CreatedDate"];
    $cartItemID = $_POST["CartItemID"];
    $productID = $_POST["ProductID"];
    $quantity = $_POST["Quantity"];

    $queryUpdate1 = "UPDATE Cart SET CreatedDate = '$createdDate' WHERE CartID = '$cartID'";
    $queryUpdate2 = "UPDATE CartItem SET CartItemID = '$cartItemID', ProductID = '$productID', Quantity = '$quantity' WHERE CartID = '$cartID'";
    if(mysqli_query($con, $queryUpdate1) && mysqli_query($con, $queryUpdate2))
    {
        session_start();
        $_SESSION["Edit"] = "Cart edited successfully!";
        header("Location: cartCRUD.php");
    }else
    {
        echo "But did I end up here?";
        die("Something's wrong editing man");
    }
}