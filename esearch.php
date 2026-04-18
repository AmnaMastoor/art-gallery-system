<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Search Exhibition</title>
<style>
  /* Reset & Base */
  * {
    box-sizing: border-box;
  }
  body {
    background: #E6E6FA; /* lavender */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: 100vh;
    color: #2c3e50;
  }
  
  /* Header */
  h1 {
    margin: 50px 0 30px;
    font-weight: 700;
    font-size: 2.5rem;
    color: #4B0082;
    background: #fff;
    padding: 15px 35px;
    border-radius: 15px;
    border: 3px solid #808080;
    user-select: none;
  }
  
  /* Form Container */
  form {
    background: #fff;
    padding: 30px 40px;
    border-radius: 20px;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  label {
    font-weight: 600;
    font-size: 1.1rem;
    color: #4B0082;
  }

  input[type="text"] {
    width: 100%;
    padding: 12px 15px;
    font-size: 1.1rem;
    border: 2px solid #ccc;
    border-radius: 12px;
    transition: border-color 0.3s ease;
  }
  input[type="text"]:focus {
    border-color: mediumslateblue;
    outline: none;
  }

  button.btn {
    background-color: forestgreen;
    color: white;
    font-weight: 700;
    font-size: 1.2rem;
    border: none;
    border-radius: 25px;
    padding: 15px 0;
    cursor: pointer;
    box-shadow: 0 6px 0 green;
    transition: opacity 0.3s ease;
  }
  button.btn:hover {
    opacity: 0.9;
  }

  /* Messages */
  .message, .error {
    max-width: 400px;
    margin: 30px auto 50px;
    padding: 18px 25px;
    border-radius: 15px;
    font-weight: 700;
    font-size: 1.3rem;
    text-align: center;
  }
  .message {
    background-color: #e0f7fa;
    color: #00796b;
    box-shadow: 0 5px 15px rgba(0,121,107,0.3);
  }
  .error {
    background-color: #ffebee;
    color: #c62828;
    box-shadow: 0 5px 15px rgba(198,40,40,0.3);
  }

  /* Table */
  table {
    width: 90%;
    max-width: 700px;
    margin: 40px auto 80px;
    border-collapse: collapse;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    background: white;
  }
  th, td {
    text-align: center;
    padding: 15px 20px;
    font-weight: 600;
    border-bottom: 1px solid #ddd;
  }
  th {
    background-color: mediumslateblue;
    color: white;
    text-transform: uppercase;
  }
  tr:nth-child(even) {
    background-color: #f9f9f9;
  }

  /* Responsive */
  @media (max-width: 480px) {
    h1 {
      font-size: 2rem;
      padding: 10px 25px;
    }
    form {
      padding: 25px 30px;
      width: 90%;
    }
    button.btn {
      font-size: 1rem;
      padding: 12px 0;
    }
    table {
      width: 100%;
      font-size: 0.9rem;
    }
  }
</style>
</head>
<body>

<h1>Search Exhibition</h1>

<form action="esearch.php" method="POST" autocomplete="off" novalidate>
  <label for="E_ID">Enter Exhibition ID</label>
  <input type="text" id="E_ID" name="E_ID" placeholder="E.g. EXH123" required />
  <button type="submit" class="btn">Search</button>
</form>

<?php
$host = "localhost";
$user = "root";
$password = "amna12345";
$con = new mysqli($host, $user, $password, "art_gallery");

if ($con->connect_error) {
    echo "<div class='error'>Connection failed: " . $con->connect_error . "</div>";
} else if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $n1 = $con->real_escape_string(trim($_POST['E_ID']));
    echo "<div class='message'>You searched for Exhibition ID: <strong>" . htmlspecialchars($n1) . "</strong></div>";

    $sql = "SELECT * FROM exhibition WHERE eid='$n1'";
    $result = $con->query($sql);

    if ($result && $result->num_rows > 0) {
        echo "<table>
                <thead>
                    <tr>
                        <th>E_ID</th>
                        <th>G_ID</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['eid']) . "</td>
                    <td>" . htmlspecialchars($row['gid']) . "</td>
                    <td>" . htmlspecialchars($row['startdate']) . "</td>
                    <td>" . htmlspecialchars($row['enddate']) . "</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<div class='error'>No exhibition found with ID <strong>" . htmlspecialchars($n1) . "</strong>. Please try again.</div>";
    }
}

$con->close();
?>

</body>
</html>
