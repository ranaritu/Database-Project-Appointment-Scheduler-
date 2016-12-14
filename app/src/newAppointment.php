<?php

ob_start();
session_start();
require_once 'Dbconnect.php';

// redirect if there is no session, which means user is not logged in
if (is_null($_SESSION['user'])) {
  header("Location: EPS-main.php");
  exit;
}

$error = false;
$form_status = null;

require_once('checkAppointmentTimes.php');
require_once('checkAvailableTimes.php');
require_once('checkExclusiveTimes.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  //$conn = new mysqli ($dbhost, $username, $password, $db);
  if ($conn) {

    // Get the user input from the web page
    $user_id = $_SESSION['user'];
    if(isset($_POST['calendarID'])) {
      $calendar = $_POST['calendarID'];
    } else {
      $form_status = "No calendar selected.";
      $error = true;
    }
    $title = $_POST['title'];
    $location = $_POST['location'];
    $start_time_hours = $_POST['startTimeHours'];
    $start_time_minutes = $_POST['startTimeMinutes'];
    $stop_time_hours = $_POST['stopTimeHours'];
    $stop_time_minutes = $_POST['stopTimeMinutes'];
    $day = $_POST['calendarDay'];
    $month = $_POST['calendarMonth'];
    $year = $_POST['calendarYear'];

    $string_start_time = $month . " " . $day . ", " . $year . " " . $start_time_hours . ":" . $start_time_minutes;
    $string_stop_time = $month . " " . $day . ", " . $year . " " . $stop_time_hours . ":" . $stop_time_minutes;

    if (isset($_POST['recurring'])) {
      $recurring = $_POST['recurring'];
      $frequency = $_POST['frequency'];
      if ($frequency == "weekly") {
        $period = "week";
      } else if ($frequency == "monthly") {
        $period = "month";
      }
      if ($_POST['count'] > 1) {
        $count = $_POST['count'];
      } else {
        $count = 1;
      }
    } else {
      $recurring = null;
      $frequency = null;
      $count = 1;
      $period = "day";
    }

    // time overlap loop starts here

    for ($i = 0; $i < $count; $i++) {
      // Convert times into timestamps
      $start_time = date("Y-m-d H:i:s", strtotime("$string_start_time + $i $period"));
      $stop_time = date("Y-m-d H:i:s", strtotime("$string_stop_time + $i $period"));

      // Check that the new appointment time does not overlap with the user's other appointments

      $available_times = checkAvailableTimes($conn, $start_time, $stop_time, $calendar);
      if (!$available_times) {
        //$conn->close();
        $form_status = "Appointments are not available during the selected time. Please select a different time.";
        $error = true;
        break;
      }
      $valid_times = checkAppointmentTimes($conn, $start_time, $stop_time, $user_id);
      if (!$valid_times) {
        //$conn->close();
        $form_status = "You already have an appointment at the selected time. Please select a different time.";
        $error = true;
        break;
      }

      $exclusive_ok = checkExclusiveTimes($conn, $start_time, $stop_time, $calendar);
      if (!$exclusive_ok) {
        //$conn->close();
        $form_status = "The selected time is already filled. Please select a different time.";
        $error = true;
        break;
      }
    }

    // time overlap loop ends here

    //recurrence even loop starts here
    if ($error !== true) {
      for ($i = 0; $i <= ($count - 1); $i++) {

        $start_time = date("Y-m-d H:i:s", strtotime("$string_start_time + $i $period"));
        $stop_time = date("Y-m-d H:i:s", strtotime("$string_stop_time + $i $period"));

        // Create the SQL prepared statement
        $sql = "CALL add_appointment(?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Bind the parameters to the SQL statement
        $stmt->bind_param('ssssss', $user_id, $calendar, $title, $location, $start_time, $stop_time);

        // Execute the SQL statement
        $stmt->execute();

        if ($stmt->error != "") {
          $form_status = $stmt->error;
          // exit here
        }

        if ($stmt->affected_rows == 1) {
          $form_status = "New appointment created!";
        }

        // Close the SQL statement
        $stmt->close();
      }
    }
  }
  // Close the database connection
  // $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>New Appointment</title>
  <link rel="stylesheet" type="text/css" href="../../web/css/style.css">
</head>
<body>
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
<div class="container">
  <div id="New-Appointments" class="formContainer">
    <div class="formHeading">
      <h1>New Appointments</h1>
    </div>
    <div class="formError">
      <?php
      if($form_status != null) {
        echo $form_status;
      }?>
    </div>
    <form class="formContent" name="newAppointmentsForm" method="post" action="../../app/src/newAppointment.php">
      <div class="fieldContainer">
        <label for="calendarID">Calendar</label><br/>

        <?php
        if ($conn) {
          $calendar_result = false;
          $calendar_name = "";
          $calendar_id = "";
          $calendar_sql = "SELECT `id`, `name` FROM `Calendar`";
          $calendar_stmt = $conn->prepare($calendar_sql);
          $calendar_stmt->execute();
          $calendar_stmt->bind_result($calendar_id, $calendar_name);
          echo '<select name="calendarID" id="calendarID">';
          while ($calendar_stmt->fetch()) {
            $calendar_result = true;
            echo "<option value=" . $calendar_id . ">" . $calendar_name . "</option>";
          }
          if ($calendar_result == false) {
            $form_status = "No calendars available.";
          }
          echo '</select>';
        }
        ?>
        <span class="combodate"><select name=calendarDay class="day " style="width: auto;"><option value="1">01</option><option
              value="2">02</option><option value="3">03</option><option value="4">04</option><option
              value="5">05</option><option value="6">06</option><option value="7">07</option><option
              value="8">08</option><option value="9">09</option><option value="10">10</option><option
              value="11">11</option><option value="12">12</option><option value="13">13</option><option
              value="14">14</option><option value="15">15</option><option value="16">16</option><option
              value="17">17</option><option value="18">18</option><option value="19">19</option><option
              value="20">20</option><option value="21">21</option><option value="22">22</option><option
              value="23">23</option><option value="24">24</option><option value="25">25</option><option
              value="26">26</option><option value="27">27</option><option value="28">28</option><option
              value="29">29</option><option value="30">30</option><option
              value="31">31</option></select>&nbsp;/&nbsp;<select name="calendarMonth" class="month "
                                                                  style="width: auto;"><option value="Jan">Jan</option><option
              value="Feb">Feb</option><option value="Mar">Mar</option><option value="April">April</option><option
              value="May">May</option><option value="June">June</option><option value="July">July</option><option
              value="Aug">Aug</option><option value="Sept">Sept</option><option value="Sept">Sept/option><option
              value="Nov">Nov</option><option value="Dec">Dec</option></select>&nbsp;/&nbsp;<select name="calendarYear"
                                                                                                    class="year "
                                                                                                    style="width: auto;"><option
              value="2016">2016</option><option value="2017">2017</option><option value="2018">2018</option><option
              value="2019">2019</option><option value="2020">2020</option><option
              value="2021">2021</option></select></span>
      </div>
      <div class="fieldContainer">
        <label for="title">Title</label>
        <input type="text" id="title" name="title"/>
      </div>
      <div class="fieldContainer">
        <label for="location">Location</label>
        <input type="text" id="location" name="location"/>
      </div>
      <div class="fieldContainer">
        <label for="startTimeHours">Start time</label>
        <select name="startTimeHours" id="startTimeHours" class="hour " style="width: auto;">
          <option value=""></option>
          <option value="0">00</option>
          <option value="1">01</option>
          <option value="2">02</option>
          <option value="3">03</option>
          <option value="4">04</option>
          <option value="5">05</option>
          <option value="6">06</option>
          <option value="7">07</option>
          <option value="8">08</option>
          <option value="9" selected>09</option>
          <option value="10">10</option>
          <option value="11">11</option>
          <option value="12">12</option>
          <option value="13">13</option>
          <option value="14">14</option>
          <option value="15">15</option>
          <option value="16">16</option>
          <option value="17">17</option>
          <option value="18">18</option>
          <option value="19">19</option>
          <option value="20">20</option>
          <option value="21">21</option>
          <option value="22">22</option>
          <option value="23">23</option>
        </select>&nbsp;:&nbsp;<select name="startTimeMinutes" id="startTimeMinutes" class="minute "
                                      style="width: auto;">
          <option value=""></option>
          <option value="0" selected>00</option>
          <option value="5">05</option>
          <option value="10">10</option>
          <option value="15">15</option>
          <option value="20">20</option>
          <option value="25">25</option>
          <option value="30">30</option>
          <option value="35">35</option>
          <option value="40">40</option>
          <option value="45">45</option>
          <option value="50">50</option>
          <option value="55">55</option>
        </select>
      </div>
      <div class="fieldContainer">
        <label for="stopTimeHours">Stop time</label>
        <select name="stopTimeHours" id="stopTimeHours" class="hour " style="width: auto;">
          <option value=""></option>
          <option value="0">00</option>
          <option value="1">01</option>
          <option value="2">02</option>
          <option value="3">03</option>
          <option value="4">04</option>
          <option value="5">05</option>
          <option value="6">06</option>
          <option value="7">07</option>
          <option value="8">08</option>
          <option value="9">09</option>
          <option value="10" selected>10</option>
          <option value="11">11</option>
          <option value="12">12</option>
          <option value="13">13</option>
          <option value="14">14</option>
          <option value="15">15</option>
          <option value="16">16</option>
          <option value="17">17</option>
          <option value="18">18</option>
          <option value="19">19</option>
          <option value="20">20</option>
          <option value="21">21</option>
          <option value="22">22</option>
          <option value="23">23</option>
        </select>&nbsp;:&nbsp;<select name="stopTimeMinutes" id="stopTimeMinutes" class="minute " style="width: auto;">
          <option value=""></option>
          <option value="0" selected>00</option>
          <option value="5">05</option>
          <option value="10">10</option>
          <option value="15">15</option>
          <option value="20">20</option>
          <option value="25">25</option>
          <option value="30">30</option>
          <option value="35">35</option>
          <option value="40">40</option>
          <option value="45">45</option>
          <option value="50">50</option>
          <option value="55">55</option>
        </select>
      </div>
      <div class="fieldContainer checkbox-field">
        <label for="recurring">recurring?</label>
        <input type="checkbox" id="recurring" name="recurring"/>
      </div>
      <div class="fieldContainer">
        <label></label>
        <select name="frequency" id="frequency">
          <option value="weekly">Weekly</option>
          <option value="monthly">Monthly</option>
        </select>
      </div>
      <div class="fieldContainer">
        <label for="count">count</label>
        <input type="text" id="count" name="count"/>
      </div>
      <div class="fieldContainer center-field">
        <input type="submit" name="submit" value="Submit"/>
      </div>
      <div class="fieldContainer center-field">
        <a href="logout.php?logout"> Logout</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
