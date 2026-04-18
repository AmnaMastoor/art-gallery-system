<html>
<head>
    <title>Search Artist</title>
</head>
<style>
    table {
        border-collapse: collapse;
        width: 60%;
        margin-left: 280px;
    } 
    th, td {
        text-align: center;
        padding: 8px;
        border-radius: 12px;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
        font-family: "arial";
        font-weight: bold;
    }
    th {
        background-color: mediumslateblue;
        color: white;
        font-family: "verdana";
        font-weight: bold;
    }
    input[type=text] {
        width: 110px;
        box-sizing: border-box;
        border: 2px solid #ccc;
        border-radius: 9px;
        font-size: 30px;
        background-color: white;
        padding: 22px 20px 22px 10px;
        transition: width 0.4s ease-in-out;
        font-weight: bold;
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
        background-color: forestgreen;
        color: white;
        padding: 16px 10px;
        margin: 8px 20px 20px 50px;
        border-radius: 24px;
        cursor: pointer;
        width: 10%;
        opacity: 0.7;
        font-family: "verdana";
        font-weight: bold;
        box-shadow: 0px 6px 0px green;
        transition: all .2s ease-in-out;
        transform: translate(0, 4px);
    }
    .btn:hover {
        opacity: 1;
        background-color: forestgreen;
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
        border-radius: 8px;
        text-align: center;
        font-size: 30px;
        margin-left: 20px;
        display: inline-block;
        font-weight: bold;
        padding: 20px;
    }
</style>
<body style="background-color: lavender">
    <h1><center><font style="border:9px solid grey" face="arial">SEARCH FROM ARTIST TABLE </font></center></h1>
    <form action="artistsearch.php" method="post">
        <div>Enter Artist ID:<input type="text" name="artistid"><br></div>
        <br><br>
        <button type="submit" class="btn">SEARCH</button>
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['artistid']) && !empty($_POST['artistid'])) {
    $n = $con->real_escape_string($_POST['artistid']);
    echo "<b><br>Entered Artist ID is $n <br></b>";

    $sql = "SELECT * FROM artist WHERE artistid = '$n'";
    $result = $con->query($sql);

    if ($result && $result->num_rows > 0) {
        echo "<b><br>Search Successful<br><br></b>";
        echo "<br><br><br><br><table><tr><th>Artist ID</th><th>G_ID</th><th>Cust ID</th><th>E_ID</th><th>First Name</th><th>Last Name</th><th>Birth Place</th><th>Style</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . (isset($row["artistid"]) ? $row["artistid"] : '') . "</td>
                <td>" . (isset($row["gid"]) ? $row["gid"] : '') . "</td>
                <td>" . (isset($row["custid"]) ? $row["custid"] : '') . "</td>
                <td>" . (isset($row["eid"]) ? $row["eid"] : '') . "</td>
                <td>" . (isset($row["fname"]) ? $row["fname"] : '') . "</td>
                <td>" . (isset($row["lname"]) ? $row["lname"] : '') . "</td>
                <td>" . (isset($row["birthplace"]) ? $row["birthplace"] : '') . "</td>
                <td>" . (isset($row["style"]) ? $row["style"] : '') . "</td>
            </tr>";
        }
        echo "</table>";
    } else {
        echo "<span><br><br>OOPS!!! Search Unsuccessful!<br><br>No such Artist ID exists. Please enter a correct Artist ID and try again.</span>";
    }
}

$con->close();
?>

</body>
</html>
