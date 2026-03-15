<?php
if(isset($_GET["UserID"]))
{
    $userID = $_GET["UserID"];
    include("connection.php");
    $query = "DELETE FROM Users WHERE UserID = '$userID'";
    if(mysqli_query($con, $query))
    {
        session_start();
        $_SESSION["Delete"] = "User deleted successfully!";
        header("Location: userCRUD.php");
    }
}
?>