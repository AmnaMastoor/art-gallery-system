<!DOCTYPE html>
<html>
<head>
    <title>Delete from Artwork</title>
    <style>
        b {
            font-size: 30px;
            font-family: 'Arial';
            padding: 27px 62px;
        }

        input[type=text] {
            width: 120px;
            font-weight: bold;
            box-sizing: border-box;
            border: 2px solid #ccc;
            border-radius: 9px;
            font-size: 26px;
            background-color: white;
            padding: 27px 20px 22px 10px;
            transition: width 0.4s ease-in-out;
        }

        input[type=text]:focus {
            width: 50%;
        }

        div {
            font-family: 'Verdana';
            font-size: 35px;
            font-weight: bold;
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
        }
    </style>
</head>
<body style="background-color: lavenderblush">
    <h1><center><font style="border:9px solid grey" face="arial">DELETE FROM ARTWORK TABLE</font></center></h1>

    <form action="" method="POST">
        <div>Enter Artwork ID: <input type="text" name="artid"><br></div>
        <br><br>
        <button type="submit" class="btn">DELETE</button>
        <button type="reset" class="btn">RESET</button>
    </form>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "amna12345";
    $dbname = "art_gallery";

    $con = new mysqli($servername, $username, $password, $dbname);

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $n = $_POST['artid'];

        if (!empty($n)) {
            $check_sql = "SELECT * FROM artwork WHERE artid = '$n'";
            $result = mysqli_query($con, $check_sql);

            if (mysqli_num_rows($result) > 0) {
                // Step 1: Find all custid values linked to this artwork
                $custid_result = mysqli_query($con, "SELECT custid FROM customer WHERE artid = '$n'");
                while ($row = mysqli_fetch_assoc($custid_result)) {
                    $custid = $row['custid'];

                    // Step 2: Delete contacts linked to this customer
                    mysqli_query($con, "DELETE FROM contacts WHERE custid = '$custid'");
                }

                // Step 3: Delete customer(s) related to this artwork
                mysqli_query($con, "DELETE FROM customer WHERE artid = '$n'");

                // Step 4: Finally delete artwork
                $del_art = "DELETE FROM artwork WHERE artid = '$n'";
                if (mysqli_query($con, $del_art)) {
                    echo "<b>Artwork with ID '$n' deleted successfully.</b>";
                } else {
                    echo "<b>Error deleting from artwork: </b>" . mysqli_error($con);
                }
            } else {
                echo "<b>Record with ID '$n' not found in artwork table.</b>";
            }
        }
    }

    $con->close();
    ?>
</body>
</html>
