<?php
session_start();

// Check if the user is logged in  .
if (!isset($_SESSION['mcn'])) {
    header("Location: login.php?x=4");
    exit;
}

$mcn = $_SESSION['mcn'];

// Database connection
$mysqli = new mysqli("localhost", "root", "", "metro");

// Check for connection error
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch user details
$res = $mysqli->prepare("SELECT fname FROM signup WHERE mcn = ?");
$res->bind_param("s", $mcn);
$res->execute();
$res->bind_result($fname);
$res->fetch();
$res->close();

// Fetch balance details
$stmt = $mysqli->prepare("SELECT balance_amount FROM balance WHERE mcn = ?");
$stmt->bind_param("s", $mcn);
$stmt->execute();
$stmt->bind_result($balance);
$stmt->fetch();
$stmt->close();

// Handle recharge request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['recharge_amount'])) {
    $recharge_amount = $_POST['recharge_amount'];
    
    // Ensure valid recharge amount
    if ($recharge_amount > 0) {
        // Update balance
        $stmt = $mysqli->prepare("UPDATE balance SET balance_amount = balance_amount + ? WHERE mcn = ?");
        $stmt->bind_param("ds", $recharge_amount, $mcn);
        $stmt->execute();
        $stmt->close();
        
        // Set success message
        $message = "Recharge Successful! New Balance: " . ($balance + $recharge_amount);
    } else {
        $message = "Please enter a valid recharge amount.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DMRC - Balance</title>
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet" type="text/css">
</head>
<body background="white" class="home">
    <!-- Wrapper -->
    <div id="wrapper" class="win-min-height">
        <header id="header" class="header block background01">
            <div class="container header_block">
                <div class="row">
                    <div class="social-icon"></div>
                    <nav class="navbar navbar-default">
                        <div class="container-fluid">
                            <div class="navbar-header">
                                <a class="navbar-brand" href="#">
                                    <img class="img-responsive" src="images/logo.png" alt="">
                                </a>
                                <div class="menu-btn">
                                    <button type="button" class="navbar-toggle collapsed color02" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                        <strong><span class="sr-only">Toggle navigation</span><span class="icon-bar background02"></span><span class="icon-bar background02"></span><span class="icon-bar background02"></span></strong>
                                        <strong>MENU</strong>
                                    </button>
                                </div>
                            </div>
                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                <ul class="nav navbar-nav color02">
                                    <li><a href="user.php">Home</a></li>
                                    <li><a href="balance.php">Balance</a></li>
                                    <li><a href="balance.php">Recharge</a></li>
                                    <li><a href="logout.php"><font color="red">Logout</font></a></li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Balance Section -->
        <section id="balance">
            <div class="container">
                <h2>Welcome, <?php echo htmlspecialchars($fname); ?>!</h2>
                <div style="margin: 20px 0;">
                    <h3>Your Current Balance: ₹<?php echo number_format($balance, 2); ?></h3>
                </div>

                <?php
                // Displaying messages
                if (isset($message)) {
                    $message_style = 'color: white; background-color: #2ecc71; padding: 15px; border-radius: 8px; font-size: 20px; font-weight: bold; text-align: center;';
                    echo "<div style='$message_style'>$message</div>";
                }
                ?>

                <!-- Recharge Form -->
                <form action="balance.php" method="POST" style="margin-top: 30px; text-align: center;">
                    <div style="margin-bottom: 20px;">
                        <label for="recharge_amount" style="font-size: 18px; font-weight: bold;">Enter Amount to Recharge: ₹</label>
                        <input type="number" name="recharge_amount" id="recharge_amount" required min="1" style="padding: 10px; font-size: 16px; width: 200px;"/>
                    </div>
                    <button type="submit" style="padding: 10px 20px; font-size: 18px; background-color: #2ecc71; color: white; border: none; border-radius: 8px;">Recharge</button>
                </form>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer id="footer" class="footer block background01">
        <div class="container">
            <div class="row">
                <div class="footerinner">
                    <div class="logo_bottom">
                        <a href="#"><img class="img-responsive" src="images/logo.png" alt=""></a>
                    </div>
                    <span class="color02 col-xs-12 col-sm-6 col-md-6">Design and Developed by <a href="https://nasa.com" target="_blank">NASA</a></span>
                </div>
            </div>
        </div>
    </footer>

    <script src="js/jquery-min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
