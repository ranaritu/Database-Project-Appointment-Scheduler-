<?php

function checkAvailableTimes($conn, $new_start_time, $new_stop_time, $calendar)
{
// date('l', $new_start_time); -> returns day of week

  $old_start_time = null;
  $old_stop_time = null;
  $avail_start_time = null;
  $avail_stop_time = null;
  $avail_day = null;
  $valid = false;
  $avail_exists = false;

  $appointment_day = date('l', strtotime($new_start_time));

  $sql = "CALL check_avail_times(?)";
  $check_stmt = $conn->prepare($sql);
  $check_stmt->bind_param('i', $calendar);
  $check_stmt->execute();
  $check_stmt->bind_result($avail_start_time, $avail_stop_time, $avail_day);

  while ($check_stmt->fetch()) {
    //when the new appointment's day of the week matches the available day of the week
    $avail_exists = true;
    if ($appointment_day == $avail_day) {
      //make sure the new appointment's times fall within the available times
      if ((date("H:i:s", strtotime($new_start_time)) >= $avail_start_time) && (date("H:i:s", strtotime($new_stop_time)) <= $avail_stop_time)) {
        $valid = true;
      }
    }
  }
  // If there are no availability rules, the date is automatically valid
  if ($avail_exists == false) {
    $valid = true;
  }
  //$check_stmt->close();
  return $valid;
}