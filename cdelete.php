<html>
<head>
    <title>Delete from Customer</title>
</head>
<style>
b{
    font-size: 30px;
    font-family: 'Arial';
    padding: 27px 62px;
}
input[type=text] {
    width: 120px;
    box-sizing: border-box;
    border: 2px solid #ccc;
    border-radius: 9px;
    font-size: 16px;
    background-color: white;
    padding: 27px 20px 22px 10px;
    transition: width 0.4s ease-in-out;
}
input[type=text]:focus {
    width: 50%;
}
div{
    font-family: 'Verdana';
    font-size: 35px;
    font-weight:bold;
    margin-left:25px;
    margin-top: 35px;
}
.btn{
    background-color: forestgreen;
    color: white;
    padding: 16px 10px;
    margin: 8px 20px 20px 50px;
    border-radius: 24px;
    cursor: pointer;
    width: 10%;
    opacity: 0.7;
    font-family: "verdana";
    font-weight: bold;
    box-shadow: 0px 6px 0px green;
    transition: all .2s ease-in-out;
    transform: translate(0, 4px);
}
.btn:hover {
    opacity: 1;
    background-color: forestgreen;
}
</style>
<body style="background-color: lavenderblush">
    <h1><center><font style="border:9px solid grey" face="arial">DELETE FROM CUSTOMER TABLE </font></center></h1>
    <form action="cdelete.php" method="POST">
        <div>Enter Customer ID:<input type="text" name="custid" required><br></div>
        <br><br>
        <button type="submit" class="btn">DELETE</button>
        <button type="reset" class="btn">RESET</button>
    </form>

<?php
$servername = "localhost";
$username = "root";
$password = "amna12345";
$dbname = "art_gallery";

$con = new mysqli($servername, $username, $password, $dbname);
if ($con->connect_error) {
    die("<b>Connection failed: " . $con->connect_error . "</b>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $custid = $_POST['custid'];

    if (!empty($custid)) {
        // Check if customer exists
        $checkStmt = $con->prepare("SELECT * FROM customer WHERE custid = ?");
        $checkStmt->bind_param("s", $custid);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            // Delete dependent contacts
            $deleteContacts = $con->prepare("DELETE FROM contacts WHERE custid = ?");
            $deleteContacts->bind_param("s", $custid);
            if (!$deleteContacts->execute()) {
                echo "<b>Error deleting contacts: " . $deleteContacts->error . "</b>";
                exit;
            }
            $deleteContacts->close();

            // Delete dependent orders
            $deleteOrders = $con->prepare("DELETE FROM orders WHERE custid = ?");
            $deleteOrders->bind_param("s", $custid);
            if (!$deleteOrders->execute()) {
                echo "<b>Error deleting orders: " . $deleteOrders->error . "</b>";
                exit;
            }
            $deleteOrders->close();

            // Delete dependent preferences
            $deletePreferences = $con->prepare("DELETE FROM preferences WHERE custid = ?");
            $deletePreferences->bind_param("s", $custid);
            if (!$deletePreferences->execute()) {
                echo "<b>Error deleting preferences: " . $deletePreferences->error . "</b>";
                exit;
            }
            $deletePreferences->close();

            // Finally delete the customer
            $deleteCustomer = $con->prepare("DELETE FROM customer WHERE custid = ?");
            $deleteCustomer->bind_param("s", $custid);
            if ($deleteCustomer->execute()) {
                echo "<b>Record with Customer ID = $custid is deleted successfully.</b>";
            } else {
                echo "<b>Error deleting customer: " . $deleteCustomer->error . "</b>";
            }
            $deleteCustomer->close();

        } else {
            echo "<b>!!!Error: Record '$custid' was not found in the database.</b>";
        }
        $checkStmt->close();

    } else {
        echo "<b>Customer ID field is empty.</b>";
    }
}

$con->close();
?>
</body>
</html>
