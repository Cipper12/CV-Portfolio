<?php
include("connection.php");
include("functions.php");
session_start();
$user_data = check_login($con);

if($user_data["RoleID"] == 3)
{
    $_SESSION["No permission"] = "You do not have permission to do this";
    header("Location: prodCRUD.php");
}elseif(isset($_GET["ProductID"]))
{
    $productID = $_GET["ProductID"];
    include("connection.php");
    $query = "DELETE FROM Product WHERE ProductID = '$productID'";
    if(mysqli_query($con, $query))
    {
        $_SESSION["Delete"] = "Product deleted successfully!";
        header("Location: prodCRUD.php");
    }
}
?>