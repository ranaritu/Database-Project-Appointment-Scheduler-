<?php

ob_start();
session_start();
require_once 'Dbconnect.php';
require_once 'add_mon_fri_rule.php';

// redirect if there is no session, which means user is not logged in
if (is_null($_SESSION['user'])) {
  header("Location: EPS-main.php");
  exit;
}

$result = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if ($conn) {
    $calendar_name = $_POST['calendarName'];
    if (isset($_POST["exclusive"]) == "on") {
      $exclusive = 1;
    } else {
      $exclusive = 0;
    }

    $sql = "CALL create_calendar(?, ?, @calendar_id)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $calendar_name, $exclusive);
    $stmt->execute();

    //1062 is the MySQL error code for duplicate entry.

    if ($stmt->errno == 1062) {
      $form_status = "A calendar with that name already exists";
    }

    if ($stmt->affected_rows == 1) {
      $form_status = "Calendar " . '"' . $calendar_name . '"' . " has been created!";

      if (isset($_POST["default9to5"]) == "on") {
        $rs = $conn->query("SELECT @calendar_id AS calID");
        while ($row = $rs->fetch_object()) {
          $calendar_id = $row->calID;
        }
        addMonFri($conn, $calendar_id);
      }
    }
    $stmt->close();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Appointments</title>
  <link rel="stylesheet" type="text/css" href="../../web/css/style.css">
<body>
<!--<center><h1><font>New Calendar</font></center>-->
</h1>
<table style="position:absolute;right:33px;top:107px;">
  <tr>
    <td>
      <?php
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
<div class="container">
  <div id="newCalendar" class="formContainer">
    <div class="formHeading">
      <h1>New Calendar</h1>
    </div>
    <div class="formError">
      <?php echo $form_status; ?>
    </div>
    <form class="formContent" name="newCalendarForm" method="post" action="../../app/src/newCalendar.php">
      <div class="fieldContainer">
        <label for="calendarName">New calendar name</label>
        <input type="text" id="calendarName" name="calendarName"
      </div>
      <div class="fieldContainer">
        <label for="exclusive">Exclusive?</label>
        <input type="checkbox" id="exclusive" name="exclusive"
      </div>
      <div class="fieldContainer">
        <label for="default9to5">Use default work week availability?</label>
        <input type="checkbox" id="default9to5" name="default9to5"/>
      </div>
      <div class="fieldContainer center-field">
        <input type="submit" name="submit" value="Submit"/>
      </div>
    </form>
  </div>
</div>
</body>
</html>