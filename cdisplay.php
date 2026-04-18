<!DOCTYPE html>
<html>
<head>
 <title>Customer Display</title>
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
  tr:nth-child(even) {
    background-color: #f2f2f2; 
    border-radius: 14px;
  }
 </style>
</head>
<body style="background-color: lavender">
  <h1><center><font style="border:9px solid grey">DISPLAY CONTENTS /\/ CUSTOMER TABLE</font></center></h1>
 <table>
 <tr>
  <th>Cust ID</th>  
  <th>Customer Name</th>
  <th>Date of Birth</th>
  <th>Address</th>
  <th>Phone</th>
  <th>Gallery</th>
  <th>Artwork</th>
 </tr>

<?php

$con = mysqli_connect("localhost", "root", "amna12345", "art_gallery");

if ($con->connect_error) {
   die("Connection failed: " . $con->connect_error);
} 

$sql = "
SELECT 
  Cu.custid, 
  Cu.fname, 
  Cu.lname, 
  Cu.dob, 
  Cu.address, 
  GROUP_CONCAT(DISTINCT Co.phone SEPARATOR ', ') AS phones,
  g.gname,
  GROUP_CONCAT(DISTINCT aw.title SEPARATOR ', ') AS artworks
FROM customer Cu
LEFT JOIN contacts Co ON Cu.custid = Co.custid
LEFT JOIN orders o ON o.custid = Cu.custid
LEFT JOIN artwork aw ON aw.artid = o.artid
LEFT JOIN gallery g ON g.gid = Cu.gid
GROUP BY Cu.custid, Cu.fname, Cu.lname, Cu.dob, Cu.address, g.gname
";

$result = mysqli_query($con, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($con));
}

echo "Number of rows: " . mysqli_num_rows($result);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>" . htmlspecialchars($row["custid"]) . "</td>
                <td>" . htmlspecialchars($row["fname"]) . " " . htmlspecialchars($row["lname"]) . "</td>
                <td>" . htmlspecialchars($row["dob"]) . "</td>
                <td>" . htmlspecialchars($row["address"]) . "</td>
                <td>" . htmlspecialchars($row["phones"] ?? 'N/A') . "</td>
                <td>" . htmlspecialchars($row["gname"] ?? 'N/A') . "</td>
                <td>" . htmlspecialchars($row["artworks"] ?? 'N/A') . "</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='7'>No results found</td></tr>";
}

mysqli_close($con);
?>


</table>
</body>
</html>
