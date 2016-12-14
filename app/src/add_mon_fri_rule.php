<?php

function addMonFri($conn, $calendar_id)
{
  $days = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday");
  $start_time = '9:00';
  $stop_time = '17:00';
  $count = count($days);


  if ($conn) {


    $sql = "CALL add_avail_rule(?, ?, ?, ?)";
    for ($i = 0; $i < $count; $i++) {

      $check_stmt = $conn->prepare($sql);

      $check_stmt->bind_param('isss', $calendar_id, $days[$i], $start_time, $stop_time);
      $check_stmt->execute();
      $check_stmt->close();
    }
    $conn->close();
  }

}

