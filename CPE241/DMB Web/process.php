<?php

include("connection.php");

if(isset($_POST["Create"]))
{
    $productID = $_POST["ProductID"];
    $categoryID = $_POST["CategoryID"];
    $productName = $_POST["ProductName"];
    $productPrice = $_POST["Price"];
    $productDiscount = $_POST["Discount"];
    $productDescription = $_POST["Description"];
    $productStock = $_POST["StockQuantity"];

    $query = "INSERT INTO Product (ProductID, CategoryID, ProductName, Price, Discount, Description, StockQuantity) VALUES ('$productID', '$categoryID', '$productName', '$productPrice', '$productDiscount', '$productDescription', '$productStock' )";

    if(mysqli_query($con, $query))
    {
        session_start();
        $_SESSION["Create"] = "Product added successfully!";
        header("Location: prodCRUD.php");
    }else
    {
        die("Something's wrong with inserting man");
    }
}

if(isset($_POST["Edit"]))
{
    $productID = $_POST["ProductID"];
    $categoryID = $_POST["CategoryID"];
    $productName = $_POST["ProductName"];
    $productPrice = $_POST["Price"];
    $productDiscount = $_POST["Discount"];
    $productDescription = $_POST["Description"];
    $productStock = $_POST["StockQuantity"];

    $queryUpdate = "UPDATE Product SET CategoryID = '$categoryID', ProductName = '$productName', Price = '$productPrice', Discount = '$productDiscount', Description = '$productDescription', StockQuantity = '$productStock' WHERE ProductID = '$productID'";
    if(mysqli_query($con, $queryUpdate))
    {
        session_start();
        $_SESSION["Edit"] = "Product edited successfully!";
        header("Location: prodCRUD.php");
    }else
    {
        echo "But did I end up here?";
        die("Something's wrong editing man");
    }
}