<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Show errors
try {
    $conn = new mysqli('localhost', 'root', 'amna12345', 'art_gallery');
    $conn->set_charset("utf8mb4");

    // Get POST values
    $artid = $_POST['artid'];
    $title = $_POST['title'];
    $year = $_POST['year'];
    $type = $_POST['type_of_art'];
    $price = $_POST['price'];
    $eid = $_POST['E_ID'];
    $gid = $_POST['G_ID'];
    $artistid = $_POST['artistid'];

    // Debug: Print the values
    echo "<pre>";
    print_r($_POST);
    echo "✅ Reached insert block.";

    $stmt = $conn->prepare("INSERT INTO artwork (artid, title, year, type_of_art, price, eid, gid, artistid)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssisdsss", $artid, $title, $year, $type, $price, $eid, $gid, $artistid);
    $stmt->execute();

    echo "✅ Artwork successfully added!";

    $stmt->close();
    $conn->close();

} catch (mysqli_sql_exception $e) {
    echo "❌ MySQL error: " . $e->getMessage();
}
?>
