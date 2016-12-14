<?php

//http://www.codingcage.com/2015/01/user-registration-and-login-script-using-php-mysql.html

ob_start();
session_start();

?>
  <!DOCTYPE html>
  <head>
    <title>541-Project</title>
    <link type="text/css" rel="stylesheet" href="../../web/css/style.css"/>
  </head>

  <body background="../../web/images/b2.jpg">
  <br>

  <center>
    <h1 style="width:1290px;
height:55px;
text-align:center;
background-color:#7778ff;
line-height:50px;
border-radius:5px;
line-height:50px;">
      <font size="7" face="mistral">Appointment Scheduler</font></h1></center>

  <style>
    .nav ul {
      list-style: none;
      margin: 0px;
      padding: 0px;
      margin: 0px;
    }

    .nav li {
      float: left;
      position: relative;
      border-radius: 15px;
      width: 110px;
      height: 30px;
      line-height: 30px;
      margin: 1px;
      background-color: silver;
      text-align: center;
    }

    .nav li ul li {
      float: none;
      text-align: left;
      padding-left: 40px;
      border-radius: 100%;
      width: 130px;
      background-color: silver;
    }

    .nav a {
      text-decoration: none;
      color: white;
    }

    .nav li ul {
      position: absolute;
      top: 30px;
      left: 0px;
      visibility: hidden;
      padding-top: 8px;
    }

    .nav li:hover ul {
      visibility: visible;
    }

    .nav li:hover {
      background-color: #FCDFFF;
    }

    .nav li ul li:hover {
      background-color: #e2e2e2;
    }

    .nav li:active {
      background-color: lightgreen;
    }

    .nav li ul li:active {
      background-color: thistle;
    }

    .nav li ul li a {
      color: black;
    }

    .content {
      clear: both;
    }
  </style>
  <div class="nav" style="position:relative;left:120px;top:-7px;">
    <ul>
      <li>
        <a href=""
           style="background-color:#EED5D2;border-top-left-radius:180px;border-bottom-right-radius:180px;color:#4F4F4F;">
          <font size="4">HOME</font></a>
      </li>

      <li>
        <a href="newAppointment.php"
           style="background-color:#C1FFC1;border-top-left-radius:180px;border-bottom-right-radius:180px;color:black;"><font
            size="2">Make Appointment</font></a>
      </li>

      <li><a href="getAppointments.php"
             style="background-color:white;border-top-left-radius:180px;border-bottom-right-radius:180px;color:black;"><font
            size="2">My Appointments</font></a>

      </li>

      <li><a href="newCalendar.php"
             style="background-color:thistle;border-top-left-radius:180px;border-bottom-right-radius:180px;color:black;"><font
            size="2">New Calendar</font></a>
      </li>
<!--      <li><a href="calendar.php"-->
<!--             style="background-color:#C1FFC1;border-top-left-radius:180px;border-bottom-right-radius:180px;color:black;"><font-->
<!--            size="2">My Calendar</font></a>-->

      </li>
      <li><a href="profile.php"
             style="background-color:#EED5D2;border-top-left-radius:180px;border-bottom-right-radius:180px;color:#4F4F4F;"><font
            size="2"> My Profile</a></font></a></li>
<!--      <li><a href="#"-->
<!--             style="background-color:#EED5D2;border-top-left-radius:180px;border-bottom-right-radius:180px;color:#4F4F4F;"><font-->
<!--            size="2">HELP</font></a></li>-->
    </ul>
  </div>
  <br>
  <br>

  <div style="position:relative;left:1100px;bottom:40px;">
    <a href="index.php">SignIn</a>&nbsp;&nbsp;
    <a href="register.php">SignUp</a>&nbsp;&nbsp;
    <a href="logout.php?logout">SignOut</a>
    <?php
    if (isset($_SESSION['name']) != "") {
      echo "Logged in as " . $_SESSION['name'];
    }
    ?>

  </div>


  <div style="width:1290px;height:400px;background-color:whitesmoke;">
    <marquee behavior="alternate" scrollamount="5">
      <img src="../../web/images/s1.png" width="400" height="400">&nbsp;&nbsp;
      <img src="../../web/images/s2.jpg" width="400" height="400">&nbsp;&nbsp;
      <img src="../../web/images/s3.jpg" width="400" height="400">&nbsp;&nbsp;
      <img src="../../web/images/s4.jpg" width="400" height="400">&nbsp;&nbsp;
      <img src="../../web/images/s5.jpg" width="400" height="400">&nbsp;&nbsp;
      <img src="../../web/images/s6.jpg" width="400" height="400">&nbsp;&nbsp;
      <img src="../../web/images/s7.png" width="400" height="400">&nbsp;&nbsp;
      <img src="../../web/images/s8.jpg" width="400" height="400"></marquee>
  </div>
  <br>

  </body>
<?php ob_end_flush(); ?>