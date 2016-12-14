<?php

function checkAppointmentTimes($conn, $new_start_time, $new_stop_time, $user_id)
{
  $old_start_time = null;
  $old_stop_time = null;
  $avail_start_time = null;
  $valid = true;


  $sql = "CALL check_time_overlap(?)";
  $check_stmt = $conn->prepare($sql);

  $check_stmt->bind_param('s', $user_id);

  $check_stmt->execute();

  // For users we only care about start & stop times; the calendar doesn't matter
  // These times are in datetime format
  $check_stmt->bind_result($old_start_time, $old_stop_time);

  while ($check_stmt->fetch()) {
    if (($new_start_time <= $old_stop_time) && ($new_stop_time >= $old_start_time)) {
      $valid = false;
    }
  }
  //$check_stmt->close();
  return $valid;
}