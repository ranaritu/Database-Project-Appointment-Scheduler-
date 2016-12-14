<?php

// Check times for calendars that do not allow multiple users to have appointments at the same time

function checkExclusiveTimes($conn, $new_start_time, $new_stop_time, $calendar)
{
  $old_start_time = null;
  $old_stop_time = null;
  $avail_start_time = null;
  $valid = true;
  $exclusive = null;
  $result = null;

  $exclusive_sql = "CALL check_calendar_exclusive(?)";
  $exclusive_stmt = $conn->prepare($exclusive_sql);
  $exclusive_stmt->bind_param('s', $calendar);
  $exclusive_stmt->execute();
  $exclusive_stmt->bind_result($exclusive);
  $exclusive_stmt->fetch();
  $exclusive_stmt->close();

  if ($exclusive) {

    $sql = "CALL check_calendar_appointments(?)";
    $check_stmt = $conn->prepare($sql);

    $check_stmt->bind_param('s', $calendar);

    $check_stmt->execute();

    $check_stmt->bind_result($old_start_time, $old_stop_time);

    while ($check_stmt->fetch()) {
      if (($new_start_time <= $old_stop_time) && ($new_stop_time >= $old_start_time)) {
        $valid = false;
      }
    }
    //$check_stmt->close();
  }
  return $valid;
}