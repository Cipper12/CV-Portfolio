<?php
session_start();
include("connection.php");
include("functions.php");
$user_data = check_login($con);

$userID = $user_data["UserID"];
$productID = $_GET["ProductID"];

$query = "INSERT INTO Cart(UserID) VALUE '$userID'";
echo "made it here 1";
$result = mysqli_query($con, $query);
echo "made it here 2";
$cartID = $result["cartID"];
$query = "INSERT INTO CartItem(CartItemID, CartID, ProductID, Quantity) VALUES (NULL, '$cartID', '$productID', 1)";
echo "made it here 3";
mysqli_query($con, $query);
echo "made it here 4";
header("Location: customerIndex.php");
/*if(isset($_POST["Create"]))
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
?>
*/
