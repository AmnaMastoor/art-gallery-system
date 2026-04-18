<!DOCTYPE html>
<html>
<head>
    <title>Search Gallery</title>
    <style>    
        table {
            border-collapse: collapse;
            width: 60%;
            margin: 30px auto;
            font-family: "verdana";
            font-weight: bold;
            font-size: 18px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        } 
        th, td {
            text-align: center;
            padding: 12px 15px;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;   
        }
        th {
            background-color: #6495ed;
            color: white;
            font-family: "verdana";
            font-weight: bold; 
            font-size: 20px;   
        }
        input[type=text] {
            width: 200px;
            box-sizing: border-box;
            border: 2px solid #ccc;
            border-radius: 19px;
            font-size: 16px;
            background-color: white;
            padding: 15px 20px 15px 15px;
            font-weight: bold;
            font-size: 22px;
            transition: width 0.4s ease-in-out;
        }
        input[type=text]:focus {
            width: 60%;
            outline: none;
            border-color: #6495ed;
            box-shadow: 0 0 5px #6495ed;
        }
        div.input-label {
            font-family: "verdana";
            font-weight: bold;
            font-size: 28px;
            margin: 30px 0 10px 25px;
        }
        button.btn {
            background-color: green;
            color: white;
            padding: 16px 10px;
            margin: 8px 20px 20px 50px;
            border-radius: 24px;
            cursor: pointer;
            width: 12%;
            opacity: 0.85;
            font-family: "verdana";
            font-weight: bold;
            box-shadow: 0px 6px 0px green;
            transition: all 0.2s ease-in-out;
            transform: translate(0, 4px);
        }
        button.btn:hover {
            opacity: 1;
            background-color: #2e8b57;
            box-shadow: 0px 8px 0px #2e8b57;
        }
        b.message {
            display: block;
            font-family: "verdana";
            background-color: #dff0d8;
            color: #3c763d;
            margin: 20px auto;
            border-radius: 8px;
            text-align: center;
            font-size: 24px;
            width: 60%;
            padding: 12px;
        }
        span.error-message {
            display: block;
            font-family: "verdana";
            background-color: #f2dede;
            color: #a94442;
            margin: 20px auto;
            border-radius: 8px;
            text-align: center;
            font-size: 24px;
            width: 60%;
            padding: 12px;
        }
        h1 {
            border: 9px solid mediumslateblue;
            font-family: Arial, sans-serif;
            padding: 15px 0;
            text-align: center;
            color: mediumslateblue;
            margin-top: 30px;
            border-radius: 15px;
            width: fit-content;
            margin-left: auto;
            margin-right: auto;
            font-weight: bold;
            font-size: 36px;
        }
        body {
            background-color: lavender;
        }
        form {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <h1>SEARCH FROM GALLERY TABLE</h1>

    <form action="" method="post">
        <div class="input-label">Enter Gallery ID:
            <input type="text" name="G_ID" placeholder="G_ID" required>
        </div>
        <button type="submit" class="btn">SEARCH</button>
    </form>

<?php
// Disable PHP errors from being shown in browser
ini_set('display_errors', 0);
error_reporting(0);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = "localhost";
    $user = "root";
    $password = "amna12345";
    $dbname = "art_gallery";

    $con = new mysqli($host, $user, $password, $dbname);

    if ($con->connect_error) {
        echo "<span class='error-message'>Sorry, we are unable to connect to the database at this moment. Please try again later.</span>";
        exit();
    }

    $n = trim($_POST['G_ID']);

    // Prepare statement to prevent SQL Injection
    $sql = "SELECT * FROM gallery WHERE gid = ?";
    $stmt = $con->prepare($sql);

    if (!$stmt) {
        echo "<span class='error-message'>An unexpected error occurred. Please try again later.</span>";
        exit();
    }

    $stmt->bind_param("s", $n);

    if (!$stmt->execute()) {
        echo "<span class='error-message'>An unexpected error occurred while searching. Please try again later.</span>";
        exit();
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<b class='message'>Search Successful</b>";
        echo "<table><thead><tr><th>G_ID</th><th>GNAME</th><th>LOCATION</th></tr></thead><tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . htmlspecialchars($row["gid"]) . "</td><td>" . htmlspecialchars($row["gname"]) . "</td><td>" . htmlspecialchars($row["location"]) . "</td></tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<span class='error-message'>Oops! No gallery found with the given ID. Please try again.</span>";
    }

    $stmt->close();
    $con->close();
}
?>

</body>
</html>
