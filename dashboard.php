<?php
// dashboard.php

$servername = "localhost";
$username = "root";
$password = "amna12345";
$database = "art_gallery";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Queries for dashboard stats
$revenueResult = $conn->query("SELECT SUM(price) AS revenue FROM orders");

$topArtistResult = $conn->query("SELECT CONCAT(artist.fname, ' ', artist.lname) AS full_name, SUM(orders.price) AS total_sales
    FROM orders 
    JOIN artwork ON orders.artid = artwork.artid 
    JOIN artist ON artwork.artistid = artist.artistid 
    GROUP BY artist.artistid 
    ORDER BY total_sales DESC 
    LIMIT 1");

// Fetching results
$revenue = $revenueResult->fetch_assoc()['revenue'] ?? 0;
$topArtist = $topArtistResult->fetch_assoc()['full_name'] ?? 'No sales yet';
// Most Popular Artworks
$popularArtworksResult = $conn->query("SELECT a.title, COUNT(*) AS sold_count
    FROM orders o
    JOIN artwork a ON o.artid = a.artid
    GROUP BY o.artid
    ORDER BY sold_count DESC
    LIMIT 3");

$popularArtworks = [];
if ($popularArtworksResult && $popularArtworksResult->num_rows > 0) {
    while ($row = $popularArtworksResult->fetch_assoc()) {
        $popularArtworks[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Art Gallery Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Aclonica&family=Raleway:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Raleway', sans-serif;
            background: url('./Images/Main-background.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #f0f0f0;
            position: relative;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(20, 20, 20, 0.6);
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: 0 auto;
            padding: 60px 20px;
            text-align: center;
        }

        h1 {
            font-family: 'Aclonica', sans-serif;
            font-size: 3rem;
            color: #e0e7ff;
            text-shadow: 1px 1px 6px rgba(0, 0, 0, 0.7);
            margin-bottom: 30px;
        }

        .stats-box {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 6px 15px rgba(255, 111, 145, 0.3);
            backdrop-filter: blur(10px);
        }

        .stats-box p {
            font-size: 1.5rem;
            margin: 10px 0;
            color: #ffdde1;
        }

        a.back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.1);
            color: #f0e9ff;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 111, 145, 0.7);
            box-shadow: 0 4px 10px rgba(255, 111, 145, 0.4);
        }

        a.back-link:hover {
            background: #ff6f91;
            color: white;
            box-shadow: 0 8px 20px rgba(255, 111, 145, 0.8);
            transform: translateY(-2px);
            border-color: #ff3b5f;
        }

        footer {
            text-align: center;
            font-size: 0.9rem;
            padding: 20px;
            color: #bbb;
            position: relative;
            z-index: 1;
        }

        footer a {
            color: #ff6f91;
            text-decoration: none;
        }

        footer a:hover {
            color: #ff3b5f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gallery Dashboard</h1>
        <div class="stats-box">
            <p><strong>Total Revenue:</strong> Rs. <?= number_format($revenue) ?></p>
            <p><strong>Top-Selling Artist:</strong> <?= htmlspecialchars($topArtist) ?></p>
            <p><strong>Most Popular Artworks:</strong></p>
    <?php if (!empty($popularArtworks)): ?>
        <?php foreach ($popularArtworks as $artwork): ?>
            <p><?= htmlspecialchars($artwork['title']) ?> – Sold: <?= $artwork['sold_count'] ?> times</p>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No artworks sold yet.</p>
    <?php endif; ?>
    <a class="back-link" href="frontend.html">← Back to Homepage</a>
        </div>
       
    </div>

 
    <footer>
        &copy; 2025 Art Gallery. All rights reserved. | Follow us on 
        <a href="#">Instagram</a>, 
        <a href="#">Facebook</a>, 
        <a href="#">Twitter</a>.
    </footer>
</body>
</html>
