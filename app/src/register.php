<?php
ob_start();
session_start();
if (isset($_SESSION['user']) != "") {
  header("Location: EPS-main.php");
}
include_once 'Dbconnect.php';

$error = false;

if (isset($_POST['btn-signup'])) {

  // clean user inputs to prevent sql injections

  $first_name = trim($_POST['fname']);
  $first_name = strip_tags($first_name);
  $first_name = htmlspecialchars($first_name);

  $last_name = trim($_POST['lname']);
  $last_name = strip_tags($last_name);
  $last_name = htmlspecialchars($last_name);

  $userEmail = trim($_POST['email']);
  $userEmail = strip_tags($userEmail);
  $userEmail = htmlspecialchars($userEmail);

  $userPass = trim($_POST['pass']);
  $userPass = strip_tags($userPass);
  $userPass = htmlspecialchars($userPass);

  // basic name validation
  if (empty($first_name) && empty($last_name)) {
    $error = true;
    $nameError = "Please enter your name.";
  } else if (strlen($first_name) < 3) {
    $error = true;
    $nameError = "Name must have at least 3 characters.";
  }

  //basic email validation
  if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
    $error = true;
    $emailError = "Please enter valid email address.";
  } else {
    // check email exist or not
    $query = "SELECT userEmail FROM users WHERE userEmail='$userEmail'";
    $result = mysqli_query($conn, $query);
    $count = mysqli_num_rows($result);
    if ($count != 0) {
      $error = true;
      $emailError = "Provided Email is already in use.";
    }
  }

  // password validation
  if (empty($userPass)) {
    $error = true;
    $passError = "Please enter password.";
  } else if (strlen($userPass) < 6) {
    $error = true;
    $passError = "Password must have at least 6 characters.";
  }


  $bus = $_POST['bus'];
  $gen = $_POST['gen'];
  // password encrypt using SHA256();
  $password = hash('sha256', $userPass);


  // if there's no error, continue to signup
  if (!$error) {

    $sql = "CALL add_user(?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param('ssssss', $first_name, $last_name, $bus, $userEmail, $password, $gen);
    $stmt->execute();

    //$query = "INSERT INTO users(userId, first_name,last_name, business, userEmail,userPass, gender) VALUES('$id','$first_name', '$last_name', '$bus','$userEmail','$password','$gen')";
    //$res = mysqli_query($conn, $query);

    if ($conn->affected_rows == 1) {
      $errTyp = "success";
      $errMSG = "Successfully registered, you may login now";
      unset($id);
      unset($fname);
      unset($lname);
      unset($bus);
      unset($email);
      unset($pass);
      unset($gen);
    } else {
      $errTyp = "danger";
      $errMSG = "Something went wrong, try again later...";
    }
  }
}

?>


  <!DOCTYPE html>
  <html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>E appointment Schedular</title>
    <link rel="stylesheet" href="../../web/css/style.css" type="text/css"/>
    <link rel="stylesheet" href="../../web/css/style.css" type="text/css"/>
  </head>
  <body>

  <center><h1
      style="width:1290px;height:55px;text-align:center;background-color:#7778ff;line-height:50px;border-radius:5px;line-height:50px;">
      <font size="7" face="mistral">"E-Appointment Schedular"</font></h1></center>
  <div class="container">

    <div id="login-form">
      <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">

        <div class="col-md-12">

          <div class="nav" style="position:relative;left:120px;top:-7px;">
            <h2
              style="background-color:#EED5D2;border-top-left-radius:180px;border-bottom-right-radius:180px;color:#4F4F4F;">
              <font size="6">
                <center>Sign Up</center>
              </font></h2>
          </div>


          <?php
          if (isset($errMSG)) {

            ?>
            <div class="form-group">
              <div class="alert alert-<?php echo ($errTyp == "success") ? "success" : $errTyp; ?>">
                <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
              </div>
            </div>
            <?php
          }
          ?>

<!--          Select Your User Id-->
<!--          <div class="form-group">-->
<!--            <div class="input-group">-->
<!--              <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>-->
<!--              <input type="text" name="id" class="form-control" placeholder="Enter User ID" maxlength="50"-->
<!--                     value="--><?php //echo $id ?><!--"/>-->
<!--            </div>-->
<!--            <span class="text-danger">--><?php //echo $nameError; ?><!--</span>-->
<!--          </div>-->
<!---->
<!--          </br>-->

          First Name
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
              <input type="text" name="fname" class="form-control" placeholder="Enter First Name" maxlength="50"
                     value="<?php echo $fname ?>"/>
            </div>
            <span class="text-danger"><?php echo $nameError; ?></span>
          </div>

          </br>


          Last Name
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
              <input type="text" name="lname" class="form-control" placeholder="Enter Last Name" maxlength="50"
                     value="<?php echo $lname ?>"/>
            </div>
            <span class="text-danger"><?php echo $last_nameError; ?></span>
          </div>

          </br>

          Business
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
              <input type="text" name="bus" class="form-control" placeholder="Enter Your Business" maxlength="50"
                     value="<?php echo $bus ?>"/>
            </div>

          </div>

          </br>


          Email
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
              <input type="email" name="email" class="form-control" placeholder="Enter Your Email" maxlength="40"
                     value="<?php echo $email ?>"/>
            </div>
            <span class="text-danger"><?php echo $emailError; ?></span>
          </div>

          </br>


          Password
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
              <input type="password" name="pass" class="form-control" placeholder="Enter Password" maxlength="15"/>
            </div>
            <span class="text-danger"><?php echo $passError; ?></span>
          </div>


          </br>

          Gender <input type="radio" name="gen" <?php if (isset($gender) && $gender == "female") echo "checked"; ?>
                        value="female">Female
          <input type="radio" name="gen" <?php if (isset($gender) && $gender == "male") echo "checked"; ?> value="male">Male


          </br>
          </br>


          <div class="form-group">
            <button type="submit" class="btn btn-block btn-primary" name="btn-signup">Sign Up</button>
          </div>


          <div class="form-group">
            <hr/>
          </div>

          <div class="form-group">
            <a href="index.php">Already a user..Sign in here</a>
          </div>


        </div>

      </form>
    </div>

  </div>

  </body>
  </html>
<?php ob_end_flush(); ?>