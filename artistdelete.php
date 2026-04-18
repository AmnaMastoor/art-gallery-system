<html>
<head>
    <title>Delete from Artist</title>
</head>
<style>
b{
    font-size: 30px;
    font-family: 'Arial';
    padding: 27px 62px;
}
input[type=text] {
    width: 110px;
    box-sizing: border-box;
    border: 2px solid #ccc;
    border-radius: 9px;
    font-size: 16px;
    background-color: white;
    background-position: 10px 10px; 
    background-repeat: no-repeat;
    padding: 22px 20px 22px 10px;
    -webkit-transition: width 0.4s ease-in-out;
    transition: width 0.4s ease-in-out;
    font-weight: bold;
    font-size: 30px;
}
input[type=text]:focus {
    width: 50%;
}
div{
    font-family: 'Verdana';
    font-size: 35px;
    font-weight:bold;
    margin-left:25px;
    margin-top: 35px;
}
.btn{
    background-color: forestgreen;
    color: white;
    padding: 16px 10px;
    margin: 8px 20px 20px 50px;
    border-radius: 24px;
    cursor: pointer;
    width: 10%;
    opacity: 0.7;
    align-content: center;
    font-family: "verdana";
    font-weight: bold;
    -webkit-box-shadow: 0px 6px 0px green;
    -moz-box-shadow: 0px 6px 0px green;
    box-shadow: 0px 6px 0px green;
    -webkit-transition: all .1s ease-in-out;
    -moz-transition: all .2s ease-in-out;
    transition: all .2s ease-in-out;
    -webkit-transform: translate(0, 5px) rotateX(25deg);
    -moz-transform: translate(0, 4px);
    transform: translate(0, 4px)
    }
.btn:hover 
{
    opacity: 1;
    background-color:forestgreen;
}

</style>
<body style="background-color: lavenderblush">
    <h1><center><font style="border:9px solid grey" face="arial">DELETE FROM ARTIST TABLE </font></center></h1>
    <form action="artistdelete.php" method="POST">
        <div>Enter Artist ID:<input type="text" name="artistid"><br></div>
        <br><br>
        <button type="submit" value ="Delete" class="btn">DELETE</button>
        <button type="reset" value ="Reset" class="btn">RESET</button>
    </form>
 <?php
$servername = "localhost";
$username = "root";
$password = "amna12345";
$dbname = "art_gallery";

$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    die("<b>Connection failed: " . $con->connect_error . "</b>");
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $a = trim($_POST['artistid']);

    if ($a != "") {
        // Check if artist exists
        $sql1 = $con->prepare("SELECT * FROM Artist WHERE artistid = ?");
        $sql1->bind_param("s", $a);
        $sql1->execute();
        $result = $sql1->get_result();

        if ($result->num_rows > 0) {
            // 1. Delete orders linked to artworks of this artist
            $sqlDeleteOrders = $con->prepare(
                "DELETE o FROM orders o 
                 JOIN artwork aw ON o.artid = aw.artid 
                 WHERE aw.artistid = ?"
            );
            $sqlDeleteOrders->bind_param("s", $a);
            $sqlDeleteOrders->execute();

            // 2. Delete artworks linked to the artist
            $sqlDeleteArtworks = $con->prepare("DELETE FROM artwork WHERE artistid = ?");
            $sqlDeleteArtworks->bind_param("s", $a);
            $sqlDeleteArtworks->execute();

            // 3. Delete preferences linked to the artist
            $sqlDeletePreferences = $con->prepare("DELETE FROM preferences WHERE artistid = ?");
            $sqlDeletePreferences->bind_param("s", $a);
            $sqlDeletePreferences->execute();

            // 4. Delete the artist
            $sqlDeleteArtist = $con->prepare("DELETE FROM Artist WHERE artistid = ?");
            $sqlDeleteArtist->bind_param("s", $a);
            if ($sqlDeleteArtist->execute()) {
                echo "<b>All orders, artworks, and preferences linked to artistid '$a' were deleted.<br>";
                echo "Artist record with artistid = '$a' deleted successfully.</b>";
            } else {
                echo "<b>Error deleting artist record: " . $con->error . "</b>";
            }

            $sqlDeleteOrders->close();
            $sqlDeleteArtworks->close();
            $sqlDeletePreferences->close();
            $sqlDeleteArtist->close();
        } else {
            echo "<b>!!! Error in Deleting Record !!!<br>Record '$a' was not found in database.</b>";
        }
    } else {
        echo "<b>Artist ID field is empty.</b>";
    }
    $sql1->close();
    $con->close();
}

?>
</body>
</html>
