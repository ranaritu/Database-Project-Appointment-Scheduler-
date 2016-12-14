<!DOCTYPE html>
<head>
  <title>Online B.Sc</title>
</head>

<body>
<br>
<div style="text-align: center;"><h1
    style="width:1290px;height:55px;text-align:center;background-color:#7778ff;line-height:50px;border-radius:5px;line-height:50px;">
    <span style="font-size: xx-large; font-family: mistral,serif; ">"E-Appointment Scheduler"</span></h1></div>


<div class="nav" style="position:relative;left:120px;top:-7px;">
  <ul>
    <li><a href="../../../../Desktop/541-project-master/app/src/EPS-main.php"
           style="background-color:#EED5D2;border-top-left-radius:180px;border-bottom-right-radius:180px;color:#4F4F4F;"><span
          style="font-size: medium; ">HOME</span></a></li>
    <li><a href="D:\future\Home.htm"
           style="background-color:#C1FFC1;border-top-left-radius:180px;border-bottom-right-radius:180px;color:black;"><span
          style="font-size: x-small; ">Make Appointment</span></a>

    </li>
    <li><a href="D:\future\Inspiring Blogs.htm"
           style="background-color:white;border-top-left-radius:180px;border-bottom-right-radius:180px;color:black;"><span
          style="font-size: x-small; ">My Appointments</span></a>

    </li>
    <li><a href="calendar.php"
           style="background-color:#C1FFC1;border-top-left-radius:180px;border-bottom-right-radius:180px;color:black;"><span
          style="font-size: x-small; ">My Calendar</span></a>


    </li>
    <li><a href="profile.php"
           style="background-color:#EED5D2;border-top-left-radius:180px;border-bottom-right-radius:180px;color:#4F4F4F;"><span
          style="font-size: x-small; ">My Profile</span></a></li>
    <li><a href="#"
           style="background-color:#EED5D2;border-top-left-radius:180px;border-bottom-right-radius:180px;color:#4F4F4F;"><span
          style="font-size: x-small; ">HELP</span></a></li>


  </ul>
</div>
<br>
<br>

<table style="position:absolute;right:33px;top:107px;">
  <tr>
    <td><a href="../../../../Desktop/541-project-master/app/src/EPS-main.php"
           style="background-color:#C1FFC1;border-top-left-radius:180px;border-bottom-right-radius:180px;color:black;"><span
          style="font-size: medium; ">Back</span></a></td>
    <td><a href="../../../../Desktop/541-project-master/app/src/EPS-main.php"
           style="background-color:#C1FFC1;border-top-left-radius:180px;border-bottom-right-radius:180px;color:black;"><span
          style="font-size: medium; ">Signout</span></a></td>
  </tr>
</table>

<!--Try putting a call to the php file here-->
<?php
echo '<h2>MY CALENDAR</h2>';
require_once('drawCalendar.php');
echo drawCalendar(date('m'), date('Y'));
?>

</body>