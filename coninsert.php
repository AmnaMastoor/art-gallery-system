 <?php
 $custid = $_POST['custid'];
$phone = $_POST['phone'];

$link = new mysqli('localhost','root','amna12345','art_gallery');
if($link->connect_error) {
    die('Connection error: '.$link->connect_error);
}

// Check if custid exists in customer table
$check_sql = "SELECT custid FROM customer WHERE custid = ?";
$stmt = $link->prepare($check_sql);
$stmt->bind_param("s", $custid);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    echo "Error: Customer ID '$custid' does not exist in customer table. Please add customer first.";
    $stmt->close();
    $link->close();
    exit();
}
$stmt->close();

// Insert into contacts
$insert_sql = "INSERT INTO contacts (custid, phone) VALUES (?, ?)";
$stmt = $link->prepare($insert_sql);
$stmt->bind_param("ss", $custid, $phone);

if ($stmt->execute()) {
    echo "Successfully inserted into Contacts table.";
} else {
    echo "Insert failed: " . $stmt->error;
}

$stmt->close();
$link->close();

?>
