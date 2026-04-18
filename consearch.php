<html>
<head>
    <title>Search CONTACTS</title>
    <style>    
        table {
            border-collapse: collapse;
            width: 60%;
            padding: 150px;
            margin-left: 280px;
        } 
        th, td {
            text-align: center;
            padding: 8px;
            border-radius: 12px;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;   
        }
        tr {
            font-family: "verdana";
            font-weight: bold; 
            font-size: 18px;
        }
        th {
            background-color: #6495ed;
            color: white;
            font-family: "verdana";
            font-weight: bold; 
            font-size: 20px;   
        }
        input[type=text] {
            width: 199px;
            box-sizing: border-box;
            border: 2px solid #ccc;
            border-radius: 19px;
            font-size: 16px;
            background-color: white;
            padding: 22px 20px 22px 10px;
            transition: width 0.4s ease-in-out;
            font-weight: bold;
            font-size: 30px;
        }
        input[type=text]:focus {
            width: 60%;
        }
        div {
            font-family: "verdana";
            font-weight: bold;
            font-size: 30px;
            margin-left: 25px;
            margin-top: 35px;
        }
        .btn {
            background-color: green;
            color: white;
            padding: 16px 10px;
            margin: 8px 20px 20px 50px;
            border-radius: 24px;
            cursor: pointer;
            width: 10%;
            opacity: 0.8;
            font-family: "verdana";
            font-weight: bold;
            box-shadow: 0px 6px 0px green;
            transition: all .2s ease-in-out;
            transform: translate(0, 4px);
        }
        .btn:hover {
            opacity: 1;
            background-color: green;
        }
        b {
            font-family: "verdana";
            background-color: lightcyan;
            color: black;
            margin-left: 80px;
            border-radius: 8px;
            text-align: center;
            font-size: 30px;
            width: 85%;    
        }
        span {
            font-family: "verdana";
            background-color: lightcyan;
            color: black;
            margin-top: 4px;
            border-radius: 8px;
            text-align: center;
            font-size: 30px;
            width: 35%;
            font-weight: bold;
        }
    </style>
</head>
<body style="background-color: lavender">
    <h1>
        <center>
            <font style="border:9px solid mediumslateblue" face="arial">
                SEARCH FROM CONTACTS TABLE
            </font>
        </center>
    </h1>

    <form action="consearch.php" method="post">
        <div>Enter CONTACTS ID:
            <input type="text" name="CUSTID" placeholder="CUSTID"><br>
        </div>
        <br><br>
        <button type="submit" value="Find" class="btn">SEARCH</button>
    </form>

<?php
$host = "localhost";
$user = "root";
$password = "amna12345";
$dbname = "art_gallery";

$con = new mysqli($host, $user, $password, $dbname);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['CUSTID'])) {
    $n = $con->real_escape_string(trim($_POST['CUSTID']));
    echo "<b><br>Entered CONTACTS ID is: $n<br></b>";

    $sql = "SELECT * FROM CONTACTS WHERE custid = '$n'";
    $result = $con->query($sql);

    if ($result && $result->num_rows > 0) {
        echo "<b><br>Search Successful<br><br></b>";
        echo "<table><tr><th>CUST ID</th><th>PHONE NO.</th></tr>";

        while ($row = $result->fetch_assoc()) {
            $custId = isset($row["custid"]) ? htmlspecialchars($row["custid"]) : "N/A";
            $phone = isset($row["phone"]) ? htmlspecialchars($row["phone"]) : "N/A";

            echo "<tr><td>$custId</td><td>$phone</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<span><br><br>OOPS! No such CONTACT ID found. Please enter a valid ID.</span>";
    }
}


$con->close();
?>

</body>
</html>
