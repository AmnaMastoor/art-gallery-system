<!DOCTYPE html>
<html>
<head>
 <title>Artist Display</title>
 <style>
 b{
    font-size: 30px;
    font-family: 'Arial';
    padding: 2px;
    text-align: center;
}
  table 
  {
   border-collapse: collapse;
   width: 100%;
   color: #588c7e;
   font-family: monospace;
   font-size: 25px;
   text-align: left;
   font-family:"Verdana";
   font-weight: bold;
   text-align: center;
   border-radius: 14px;
  } 
  th 
  {
   background-color: mediumpurple;
   color: white;
   border-radius: 14px;
  }
  h1{
    font-family: "Arial";
    font-size: 50px;
     color: black;
     border: 9px solid grey;
     border-radius: 17px;
  }
  td{
    padding: 12px;
    border-radius: 14px;
  }
  tr:nth-child(even) {background-color: #f2f2f2; 
    border-radius: 14px;}
 </style>
</head>
<body style="background-color: lavender">
  <h1><center><font style="border:9px solid grey">DISPLAY CONTENTS /\/ ARTIST TABLE</font></center></h1>
 <table>
 <tr>
  <th><br>Artist ID<br><br></th> 
  <th><br>E ID<br><br></th>
  <th><br>Artist Name<br><br></th>
  <th><br>BirthPlace<br><br></th>
  <th><br>Style<br><br></th>
  <th><br>Gallery<br><br></th> 
  <th><br>Customer Name<br><br></th>
  <br><br>
 </tr>
  <?php

$con = mysqli_connect("localhost", "root", "amna12345", "art_gallery");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Since artist has no custid or gid, we omit those joins
$sql = "SELECT ar.artistid, ar.fname, ar.lname, ar.birthplace, ast.style_name
        FROM artist ar
        LEFT JOIN art_style ast ON ar.style_id = ast.style_id";

if ($result = mysqli_query($con, $sql)) {
    echo "<table>
            <tr>
              <th>Artist ID</th>
              <th>Artist Name</th>
              <th>BirthPlace</th>
              <th>Style</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row["artistid"]) . "</td>
                <td>" . htmlspecialchars($row["fname"]) . " " . htmlspecialchars($row["lname"]) . "</td>
                <td>" . htmlspecialchars($row["birthplace"]) . "</td>
                <td>" . htmlspecialchars($row["style_name"]) . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

$con->close();
?>





</table>
</body>
</html>