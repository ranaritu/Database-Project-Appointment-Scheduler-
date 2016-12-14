<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Appointments</title>
  <link rel="stylesheet" type="text/css" href="../../web/css/style.css">
<body>
<div class="container">
  <div id="New-Calendar" class="formContainer">
    <div class="formHeading">
      <h1>My Appointments</h1>
    </div>
  </div>
</div>


<table style="position:absolute;right:33px;top:107px;">
  <tr>
    <td>
      <?php
      session_start();
      if (isset($_SESSION['name']) != "") {
        echo "Hello, " . $_SESSION['name'] . "!";
      }
      ?>
    </td>
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
//session_start();
require_once 'Dbconnect.php';

// redirect if there is no session, which means user is not logged in
if (is_null($_SESSION['user'])) {
  header("Location: EPS-main.php");
  exit;
}

$result = false;

if ($conn) {

  $returned_appointment = false;
  $sql = "CALL get_appointments(?)";
  $stmt = $conn->prepare($sql);

  $user_id = $_SESSION['user'];

  $stmt->bind_param('s', $user_id);

  $stmt->execute();

  $stmt->bind_result($title, $location, $start_time, $stop_time, $calendar);

  while ($stmt->fetch()) {
    $returned_appointment = true;
    //date("d-M-Y H:i", strtotime($start_time));

    echo "<div class='container'>
<div id='appointment' class='formContainer'>
<div class='appointmentContent'>
<h2>$title</h2>
          Location: $location<br />
          $calendar<br />
          Start: " . date("d-M-Y H:i", strtotime($start_time)) . "<br />
          End: " . date("d-M-Y H:i", strtotime($stop_time)) . "</p>
    
    </div>
    </div>";
  }

  if ($returned_appointment === false) {
    echo "<p>No appointments found</p>";
  }

  $stmt->close();
}
// $conn->close();

?>

