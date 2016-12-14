
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
  <body background="../../web/images/b2.jpg">
  <center><h1> My Profile</font></h1></center>
<table style="position:absolute;right:33px;top:107px;">
    <tr>
        <td><a href="../../app/src/EPS-main.php"
               style="background-color:#C1FFC1;border-top-left-radius:180px;border-bottom-right-radius:180px;color:black;"><span
                    style="font-size: medium; ">Back</span></a></td>
        <td><a href="logout.php?logout"
               style="background-color:#C1FFC1;border-top-left-radius:180px;border-bottom-right-radius:180px;color:black;"><span
                    style="font-size: medium; ">Signout</span></a></td>
    </tr>
</table>
</body>
</html>



<?php
ob_start();
 session_start();
 require_once 'dbconnect.php';
 
 // if session is not set this will redirect to login page
 if( !isset($_SESSION['user']) ) {
  header("Location: index.php");
  exit;
 }

$dbhost = "localhost";
$username = "root";
$password = "";
$db = "541-project";

// Create connection
$conn = new mysqli($dbhost, $username, $password, $db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user = $_SESSION['user'];
$sql = "SELECT userId, first_name, last_name,business,gender,userEmail FROM users WHERE userID = $user";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
       echo "Your User ID :{$row['userId']}  <br> ".
         "First Name : {$row['first_name']} <br> ".
         "Last Name : {$row['last_name']} <br> ".
		 "Profession: {$row['business']}<br>".
		 "Gender: {$row['gender']}<br>".
         "Email: {$row['userEmail']}<br>";

    }
} else {
    echo "0 results";
}
$conn->close();
?>







