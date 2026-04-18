<?php
// exhibition_insert.php

$host = "localhost";
$user = "root";
$password = "amna12345";
$dbname = "art_gallery";

// Create connection
$con = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("<div style='color:red; font-weight:bold;'>Connection failed: " . htmlspecialchars($con->connect_error) . "</div>");
}

$message = ""; // To store success or error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize POST data
    $eid = trim($_POST['E_ID']);
    $gid = trim($_POST['G_ID']);
    $startdate = trim($_POST['startdate']);
    $enddate = trim($_POST['enddate']);

    // Basic validation
    if (empty($eid) || empty($gid) || empty($startdate) || empty($enddate)) {
        $message = "<p style='color:red; font-weight:bold;'>Please fill in all fields.</p>";
    } elseif ($startdate > $enddate) {
        $message = "<p style='color:red; font-weight:bold;'>Start date cannot be after end date.</p>";
    } else {
        // Prepare statement to avoid SQL injection
        $stmt = $con->prepare("INSERT INTO exhibition (eid, gid, startdate, enddate) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            die("<p style='color:red; font-weight:bold;'>Prepare failed: " . htmlspecialchars($con->error) . "</p>");
        }

        $stmt->bind_param("ssss", $eid, $gid, $startdate, $enddate);

        if ($stmt->execute()) {
            $message = "<p style='color:green; font-weight:bold;'>Exhibition inserted successfully!</p>";
        } else {
            $message = "<p style='color:red; font-weight:bold;'>Error inserting exhibition: " . htmlspecialchars($stmt->error) . "</p>";
        }

        $stmt->close();
    }
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Insert into Exhibition</title>
  <style>
    /* Reset & base */
    * {
      box-sizing: border-box;
    }

    body {
      font-family: Verdana, Arial, Helvetica, sans-serif;
      background-color: #fff;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
      padding: 40px 20px;
      color: #2c3e50;
    }

    .container {
      background-color: #f9f9f9;
      padding: 35px 40px;
      border-radius: 20px;
      max-width: 480px;
      width: 100%;
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
      text-align: center;
    }

    h1 {
      color: #3366cc;
      font-weight: 700;
      font-size: 1.8rem;
      margin-bottom: 25px;
      border: 6px solid grey;
      border-radius: 18px;
      padding: 20px 0;
      user-select: none;
    }

    hr {
      border: 1.5px solid #ddd;
      margin: 30px 0;
    }

    label {
      display: block;
      font-weight: 700;
      font-size: 1.1rem;
      margin-bottom: 8px;
      text-align: left;
      color: #333;
    }

    input[type="text"],
    input[type="date"] {
      width: 100%;
      padding: 14px 15px;
      margin-bottom: 25px;
      border: 2px solid #ccc;
      border-radius: 12px;
      font-weight: 600;
      font-size: 1rem;
      background-color: #fafafa;
      transition: border-color 0.3s ease;
    }

    input[type="text"]:focus,
    input[type="date"]:focus {
      outline: none;
      border-color: mediumslateblue;
      background-color: #fff;
    }

    /* Buttons container for spacing */
    .btn-group {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-top: 10px;
    }

    button {
      cursor: pointer;
      font-weight: 700;
      font-family: Verdana, Arial, Helvetica, sans-serif;
      font-size: 1rem;
      border-radius: 25px;
      padding: 14px 0;
      width: 40%;
      box-shadow: 0 6px 0 rgba(0,0,0,0.12);
      transition: opacity 0.3s ease, transform 0.2s ease;
      border: none;
      user-select: none;
    }

    button:focus {
      outline: none;
      box-shadow: 0 0 5px mediumslateblue;
    }

    .registerbtn {
      background-color: forestgreen;
      color: white;
      box-shadow: 0 6px 0 green;
      transform: translateY(4px);
    }
    .registerbtn:hover {
      opacity: 0.9;
      transform: translateY(2px);
    }

    .registerbtn1 {
      background-color: darkred;
      color: white;
      box-shadow: 0 6px 0 #8b0000;
      transform: translateY(4px);
    }
    .registerbtn1:hover {
      opacity: 0.9;
      transform: translateY(2px);
    }

    /* Responsive */
    @media (max-width: 500px) {
      .container {
        padding: 30px 25px;
      }
      button {
        width: 48%;
        padding: 12px 0;
        font-size: 0.9rem;
      }
      h1 {
        font-size: 1.5rem;
        padding: 15px 0;
        border-width: 5px;
      }
    }

    /* Message styling */
    .message {
      margin: 15px 0;
      font-size: 1.2rem;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <form action="" method="POST" autocomplete="off" novalidate>
    <div class="container">
      <h1>Fill the Form with Proper Details</h1>
      <hr>

      <?php if ($message): ?>
        <div class="message" style="color: <?= strpos($message, 'successfully') !== false ? 'green' : 'red' ?>;">
          <?= $message ?>
        </div>
      <?php endif; ?>

      <label for="E_ID">Exhibition ID</label>
      <input type="text" id="E_ID" name="E_ID" placeholder="Enter Exhibition ID" required />

      <label for="G_ID">Gallery ID</label>
      <input type="text" id="G_ID" name="G_ID" placeholder="Enter Gallery ID" required />

      <label for="startdate">Start Date</label>
      <input type="date" id="startdate" name="startdate" required />

      <label for="enddate">End Date</label>
      <input type="date" id="enddate" name="enddate" required />

      <hr>

      <div class="btn-group">
        <button type="submit" class="registerbtn">Submit</button>
        <button type="reset" class="registerbtn1">Reset</button>
      </div>
    </div>
  </form>

</body>
</html>
