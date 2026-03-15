<?php

include("connection.php");

if(isset($_POST["Edit"]))
{
    $userID = $_POST["UserID"];
    $email = $_POST["Email"];
    $name = $_POST["Name"];
    $phone = $_POST["Phone"];
    $address = $_POST["Address"];
    $roleID = $_POST["RoleID"];
    $uStatus = $_POST["UStatus"];

    $queryUpdate = "UPDATE Users SET Email = '$email', Name = '$name', Phone = '$phone', Address = '$address', RoleID = '$roleID', UStatus = '$uStatus' WHERE UserID = '$userID'";
    if(mysqli_query($con, $queryUpdate))
    {
        session_start();
        $_SESSION["Edit"] = "User edited successfully!";
        header("Location: userCRUD.php");
    }else
    {
        echo "But did I end up here?";
        die("Something's wrong editing man");
    }
}