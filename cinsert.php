<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    if (
        !empty($_POST['custid']) && !empty($_POST['G_ID']) &&
        !empty($_POST['fname']) && !empty($_POST['lname']) &&
        !empty($_POST['dob']) &&
        !empty($_POST['address']) && !empty($_POST['phone'])
    ) {
        $custid = $_POST['custid'];
        $gid = $_POST['G_ID'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $dob = $_POST['dob'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];

        $link = new mysqli('localhost', 'root', 'amna12345', 'art_gallery');

        if ($link->connect_error) {
            die('Connection error: ' . $link->connect_error);
        }

        // Insert into customer table (without artid)
        $sql3 = "INSERT INTO customer (custid, gid, fname, lname, dob, address)
                 VALUES (?, ?, ?, ?, ?, ?)";
        $stmt1 = $link->prepare($sql3);
        $stmt1->bind_param("ssssss", $custid, $gid, $fname, $lname, $dob, $address);

        // Insert into contacts table
        $sql4 = "INSERT INTO contacts (custid, phone) VALUES (?, ?)";
        $stmt2 = $link->prepare($sql4);
        $stmt2->bind_param("ss", $custid, $phone);

        if ($stmt1->execute() && $stmt2->execute()) {
            echo '<p style="color: green; font-weight: bold;">✅ Successfully Inserted into Customer and Contacts tables.</p>';
        } else {
            echo '<p style="color: red;">❌ Error during insert: ' . $link->error . '</p>';
        }

        $stmt1->close();
        $stmt2->close();
        $link->close();
    } else {
        echo '<p style="color: orange;">⚠️ Form data not submitted properly or some fields are empty.</p>';
    }
}
?>
