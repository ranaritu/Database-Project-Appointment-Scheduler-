<!DOCTYPE HTML>
<html>
<head>
  <style>
    .error {
      color: #FF0000;
    }
  </style>
</head>
<body>

<?php
// define variables and set to empty values
$usernameErr = $emailErr = $genderErr = $passwordErr = "";
$username = $email = $gender = $password = $comment = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
  } else {
    $email = test_input($_POST["email"]);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
    }
  }
  if (empty($_POST["password"])) {
    $passwordErr = "password is required";
  } else {
    $password = test_input($_POST["password"]);
  }
}
if (empty($_POST["comment"])) {
  $comment = "";
} else {
  $comment = test_input($_POST["comment"]);
}

if (empty($_POST["gender"])) {
  $genderErr = "Gender is required";
} else {
  $gender = test_input($_POST["gender"]);
}


if (empty($_POST["gender"])) {
  $genderErr = "Gender is required";
} else {
  $gender = test_input($_POST["gender"]);
}


function test_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
  <!--  Username: <input type="text" name="username" value="<?php echo $username; ?>">
  <span class="error">* <?php echo $usernameErr; ?></span>
  <br><br> -->
  E-mail: <input type="text" name="email" value="<?php echo $email; ?>">
  <span class="error">* <?php echo $emailErr; ?></span>
  <br><br>
  Password: <input type="password" name="password" value="<?php echo $password; ?>">
  <span class="error">* <?php echo $passwordErr; ?></span>
  <br><br>
  Comment: <textarea name="comment" rows="5" cols="40"><?php echo $comment; ?></textarea>
  <br><br>
  Gender:
  <input type="radio" name="gender" <?php if (isset($gender) && $gender == "female") echo "checked"; ?> value="female">Female
  <input type="radio" name="gender" <?php if (isset($gender) && $gender == "male") echo "checked"; ?> value="male">Male
  <span class="error">* <?php echo $genderErr; ?></span>
  <br><br>
  <button type="submit">Login</button>
</form>

<?php
echo $username;
echo "<br>";
echo $email;
echo "<br>";
echo $password;
echo "<br>";
echo $comment;
echo "<br>";
echo $gender;
echo "<br>";
echo "login successfull";
?>

</body>
</html>

 
