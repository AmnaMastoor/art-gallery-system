<?php
// artist_profile.php

$servername = "localhost";
$username = "root";
$password = "amna12345";
$database = "art_gallery";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if artist ID is provided via GET or POST
$artistid = $_GET['id'] ?? '';

if (!$artistid && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // If POST request, get artistid from form input
    $artistid = trim($_POST['artistid'] ?? '');
}

if (!$artistid) {
    // Show a simple HTML form to ask for artist ID
    ?>
   <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Enter Artist ID</title>
    <link href="https://fonts.googleapis.com/css2?family=Aclonica&family=Raleway:wght@400;700&display=swap" rel="stylesheet" />
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(20, 20, 20, 0.7);
            z-index: 0;
        }

        form {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            padding: 30px 40px;
            width: 320px;
            box-shadow: 0 6px 25px rgba(255, 111, 145, 0.5);
            text-align: center;
        }

        h2 {
            font-family: 'Aclonica', sans-serif;
            color: #ffe3f1;
            margin-bottom: 25px;
            text-shadow: 1px 1px 8px rgba(255, 111, 145, 0.8);
            font-size: 1.9rem;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            border-radius: 12px;
            border: none;
            font-size: 1.1rem;
            margin-bottom: 20px;
            outline: none;
            box-shadow: inset 0 0 6px rgba(255, 111, 145, 0.4);
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            transition: background 0.3s ease;
        }

        input[type="text"]::placeholder {
            color: #ffd3e5;
            font-style: italic;
        }

        input[type="text"]:focus {
            background: rgba(255, 111, 145, 0.25);
            box-shadow: 0 0 10px rgba(255, 111, 145, 0.8);
        }

        input[type="submit"] {
            background: #ff6f91;
            color: white;
            font-weight: 700;
            font-size: 1.15rem;
            padding: 12px 30px;
            border: none;
            border-radius: 14px;
            cursor: pointer;
            box-shadow: 0 6px 18px rgba(255, 111, 145, 0.7);
            transition: background 0.3s ease, box-shadow 0.3s ease, transform 0.2s ease;
        }

        input[type="submit"]:hover {
            background: #ff3b5f;
            box-shadow: 0 9px 25px rgba(255, 59, 95, 0.9);
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <form method="post" action="">
        <h2>Please enter Artist ID</h2>
        <input type="text" name="artistid" placeholder="Artist ID" required />
        <input type="submit" value="Submit" />
    </form>
</body>
</html>

    <?php
    exit;
}

// Now fetch sales and artist info as before...

// Lifetime Sales Query
$query = "SELECT SUM(total_sales) AS lifetime_sales FROM artist_sales WHERE artistid = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $artistid);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$lifetimeSales = $res['lifetime_sales'] ?? 0.00;

// Monthly Sales Breakdown Query
$query = "SELECT month_year, total_sales FROM artist_sales WHERE artistid = ? ORDER BY month_year DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $artistid);
$stmt->execute();
$result = $stmt->get_result();

// Fetch artist name
$nameQuery = $conn->prepare("SELECT CONCAT(fname, ' ', lname) AS full_name FROM artist WHERE artistid = ?");
$nameQuery->bind_param("s", $artistid);
$nameQuery->execute();
$nameResult = $nameQuery->get_result()->fetch_assoc();
$artistName = $nameResult['full_name'] ?? "Unknown Artist";

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Artist Profile – <?= htmlspecialchars($artistName) ?></title>
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
            background: rgba(20, 20, 20, 0.7);
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 700px;
            margin: 60px auto;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            box-shadow: 0 6px 25px rgba(255, 111, 145, 0.4);
            backdrop-filter: blur(15px);
            padding: 40px 30px;
            text-align: center;
        }

        h1 {
            font-family: 'Aclonica', sans-serif;
            font-size: 2.8rem;
            color: #ffe3f1;
            text-shadow: 1px 1px 8px rgba(255, 111, 145, 0.9);
            margin-bottom: 20px;
        }

        h3 {
            font-weight: 700;
            font-size: 1.4rem;
            color: #ffbed8;
            margin-top: 30px;
            margin-bottom: 12px;
            text-shadow: 1px 1px 5px rgba(255, 111, 145, 0.6);
        }

        p {
            font-size: 1.25rem;
            margin: 10px 0;
            color: #ffd9e8;
            text-shadow: 1px 1px 5px rgba(255, 111, 145, 0.4);
        }

        ul {
            list-style-type: none;
            padding-left: 0;
            margin-top: 10px;
        }

        ul li {
            font-size: 1.1rem;
            margin: 6px 0;
            background: rgba(255, 111, 145, 0.15);
            border-radius: 8px;
            padding: 10px 15px;
            color: #ffd3e5;
            box-shadow: 0 2px 6px rgba(255, 111, 145, 0.3);
            transition: background 0.3s ease;
        }

        ul li:hover {
            background: rgba(255, 111, 145, 0.35);
            cursor: default;
        }

        a.back-link {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 28px;
            text-decoration: none;
            background: rgba(255, 111, 145, 0.2);
            color: #fff0f8;
            border-radius: 14px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(255, 111, 145, 0.6);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 111, 145, 0.8);
        }

        a.back-link:hover {
            background: #ff6f91;
            color: white;
            box-shadow: 0 8px 25px rgba(255, 111, 145, 0.9);
            transform: translateY(-3px);
            border-color: #ff3b5f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Artist: <?= htmlspecialchars($artistName) ?></h1>

        <h3>Total Sales for Artist</h3>
        <p><strong>Rs. <?= number_format($lifetimeSales, 2) ?></strong></p>

        <h3>Monthly Sales Breakdown</h3>
        <ul>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li><?= htmlspecialchars($row['month_year']) ?>: Rs. <?= number_format($row['total_sales'], 2) ?></li>
            <?php endwhile; ?>
        </ul>

        <a class="back-link" href="frontend.html">← Back to Home</a>
    </div>
</body>
</html>
