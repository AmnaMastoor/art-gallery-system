<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $artistid = $_POST['artistid'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $birthplace = $_POST['birthplace'];
    $style_id = $_POST['style_id'];

    // Connect to database
    $conn = new mysqli("localhost", "root", "amna12345", "art_gallery");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . htmlspecialchars($conn->connect_error));
    }

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO artist (artistid, fname, lname, birthplace, style_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $artistid, $fname, $lname, $birthplace, $style_id);

    if ($stmt->execute()) {
        echo "<script>alert('Artist successfully added.'); window.location.href='frontend.html';</script>";
    } else {
        echo "<script>alert('Error: " . addslashes($stmt->error) . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
