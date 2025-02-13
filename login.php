<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign-Up/Login Form</title>
  <link href="https://fonts.googleapis.com/css?family=Titillium+Web:400,300,600" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">

    <link rel="stylesheet" href="css/styl.css">
</head>
<body>
  <div class="form">
  <?php
$message = '';
$status = @$_GET['x'];
$style = ''; // Variable to store the inline style

if ($status == 1) {
    $message = "<h2>User ID Already Exists.</h2>";
    $style = 'color: white; background-color: #e74c3c; padding: 3px; border-radius: 8px; font-size: 12px; font-weight: bold; text-align: center; width: 500px; margin: 20px auto;';
} elseif ($status == 2) {
    $message = "<h2>User Created Successfully.</h2>";
    $style = 'color: white; background-color: #2ecc71; padding: 3px; border-radius: 8px; font-size: 12px; font-weight: bold; text-align: center; width: 500px; margin: 20px auto;';
} elseif ($status == 3 || $status == 4) {
    $message = "<h2>User ID Invalid.</h2>";
    $style = 'color: white; background-color: #f39c12; padding: 3px; border-radius: 8px; font-size: 12px; font-weight: bold; text-align: center; width: 500px; margin: 20px auto;';
}

if ($message) {
    echo "<div style='$style'>$message</div>";
}
?>

    <ul class="tab-group">
      <li class="tab active"><a href="#signup">Sign Up</a></li>
      <li class="tab"><a href="#login">Log In</a></li>
    </ul>

    <div class="tab-content">
      <!-- Sign-Up Form -->
      <div id="signup">
        <h1>Sign Up for Free</h1>
        <form action="signup.php" method="POST" name="signupForm" onsubmit="return validateSignUp()">
          <div class="field-wrap">
            <input type="text" name="t1" placeholder="First Name" required>
          </div>
          <div class="field-wrap">
            <input type="text" name="t2" placeholder="Last Name" required>
          </div>
          <div class="field-wrap">
            <input type="text" name="t3" maxlength="5" placeholder="Metro Card Number" required>
          </div>
          <div class="field-wrap">
            <input type="number" name="t4" placeholder="Mobile Number" required>
          </div>
          <div class="field-wrap">
            <input type="email" name="t5" placeholder="Email Address" required>
          </div>
          <div class="field-wrap">
            <input type="password" name="t6" placeholder="Set A Password" required minlength="4">
          </div>
          <button type="submit" class="button">Get Started</button>
        </form>
      </div>

      <!-- Login Form -->
      <div id="login">
        <h1>Welcome Back!</h1>
        <form action="log.php" method="POST" name="loginForm" onsubmit="return validateLogin()">
          <div class="field-wrap">
            <input type="text" name="t9" placeholder="Metro Card Number" required>
          </div>
          <div class="field-wrap">
            <input type="password" name="t10" placeholder="Password" required>
          </div>
          <p class="forgot"><a href="pwd1.php">Change Password...</a></p>
          <button type="submit" class="button">Log In</button>
        </form>
      </div>
    </div>
  </div>

  <script>
    // Tab Switching Logic
    $(document).ready(function() {
      $('.tab a').on('click', function(e) {
        e.preventDefault();
        $(this).parent().addClass('active');
        $(this).parent().siblings().removeClass('active');
        const target = $(this).attr('href');
        $('.tab-content > div').hide();
        $(target).fadeIn(600);
      });
    });

    // Validation for Sign-Up
    function validateSignUp() {
      const form = document.forms['signupForm'];
      const metroCardNumber = form['t3'].value.trim();
      const mobileNumber = form['t4'].value.trim();

      if (metroCardNumber.length !== 5) {
        alert("Metro card number must be 5 characters.");
        form['t3'].focus();
        return false;
      }
      if (mobileNumber.length !== 10) {
        alert("Mobile number must be 10 digits.");
        form['t4'].focus();
        return false;
      }
      return true;
    }

    // Validation for Login
    function validateLogin() {
      const form = document.forms['loginForm'];
      const metroCardNumber = form['t9'].value.trim();
      const password = form['t10'].value.trim();

      if (!metroCardNumber) {
        alert("Metro card number is required.");
        form['t9'].focus();
        return false;
      }
      if (!password) {
        alert("Password is required.");
        form['t10'].focus();
        return false;
      }
      return true;
    }
  </script>
</body>
</html>
