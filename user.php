<?php
// Start session
session_start();

// Check if the user is logged in
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

// If the form is submitted, process the input
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get selected stations
    $fromStation = $_POST['t7'];
    $toStation = $_POST['t8'];

    // Check if valid stations are selected
    if ($fromStation && $toStation && $fromStation !== $toStation) {
        // Calculate fare based on station pair
        $fare = calculateFare($fromStation, $toStation);

        // Save the ride into the database
        $date = date('Y-m-d H:i:s'); // Get the current date and time
        $user_name = $fname;

        $stmt = $mysqli->prepare("INSERT INTO rides (mcn1, user_name1, frm1, to1, fare, date1) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssds", $mcn, $user_name, $fromStation, $toStation, $fare, $date);
        
        if ($stmt->execute()) {
            echo "<h3>Fare from $fromStation to $toStation: ₹$fare</h3>";
            echo "<p>Your ride has been saved successfully!</p>";
        } else {
            echo "<p>Error: Could not save the ride.</p>";
        }

        $stmt->close();
    } else {
        echo "<h3>Please select valid stations.</h3>";
    }
}

// Function to calculate fare (Example, replace with your logic)
function calculateFare($from, $to) {
    // Example logic for fare, you can replace with your real fare logic
    $stationFare = [
        'AKSHARDHAM' => 10,
        'DWARKA' => 20,
        'GOLF COURSE' => 30,
        'INDRAPRASTHA' => 40,
        'KAROL BAGH' => 50,
        'MANDI HOUSE' => 60,
        'PRAGATI MAIDAN' => 70,
        'RAJOURI GARDEN' => 80,
        'YAMUNA BANK' => 90,
    ];
    
    // Check if both stations are in the list
    if (isset($stationFare[$from]) && isset($stationFare[$to])) {
        return abs($stationFare[$from] - $stationFare[$to]); // Simple example fare calculation
    }
    return 0;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DMRC</title>
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }

        .table-container {
            margin: 20px auto;
            padding: 20px;
            max-width: 90%;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
        }

        table th,
        table td {
            padding: 12px 20px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #547D7F;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: white;
        }

        table tr:hover {
            background-color: #f4f4f4;
        }

        .form-container {
            margin: 20px auto;
            max-width: 80%;
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container select,
        .form-container input[type="submit"] {
            padding: 10px;
            width: 100%;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .form-container input[type="submit"] {
            background-color: #A3AEBC;
            color: white;
            cursor: pointer;
        }

        .form-container input[type="submit"]:hover {
            background-color: #07456C;
        }

        .welcome-message {
            margin: 20px;
            font-size: 24px;
            font-weight: bold;
        }
    </style>
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
                                    <li class="active"><a href="#wrapper">Home</a></li>
                                    <li><a href="#rides">Past Rides</a></li>
                                    <li><a href="balance.php">Balance</a></li>
                                    <li><a href="#recharge">Recharge</a></li>
                                    <li><a href="#counter">Features</a></li>
                                    <li><a href="#team">Team</a></li>
                                    <li><a href="logout.php"><font color="red">Logout</font></a></li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </header>

        <section id="home" class="block background01 animatedParent animateOnce">
            <div class="welcome-message">
                <h2>Welcome, <?php echo htmlspecialchars($fname); ?></h2>
            </div>

            <!-- Form to select stations -->
            <div class="form-container">
                <form action="user.php" method="POST">
                    <label for="from">From</label>
                    <select name="t7">
                        <option selected="selected">CHOOSE A STATION</option>
                        <option value="AKSHARDHAM">AKSHARDHAM</option>
                        <option value="DWARKA">DWARKA</option>
                        <option value="GOLF COURSE">GOLF COURSE</option>
                        <option value="INDRAPRASTHA">INDRAPRASTHA</option>
                        <option value="KAROL BAGH">KAROL BAGH</option>
                        <option value="MANDI HOUSE">MANDI HOUSE</option>
                        <option value="PRAGATI MAIDAN">PRAGATI MAIDAN</option>
                        <option value="RAJOURI GARDEN">RAJOURI GARDEN</option>
                        <option value="YAMUNA BANK">YAMUNA BANK</option>
                    </select>

                    <label for="to">To</label>
                    <select name="t8">
                        <option selected="selected">CHOOSE A STATION</option>
                        <option value="AKSHARDHAM">AKSHARDHAM</option>
                        <option value="DWARKA">DWARKA</option>
                        <option value="GOLF COURSE">GOLF COURSE</option>
                        <option value="INDRAPRASTHA">INDRAPRASTHA</option>
                        <option value="KAROL BAGH">KAROL BAGH</option>
                        <option value="MANDI HOUSE">MANDI HOUSE</option>
                        <option value="PRAGATI MAIDAN">PRAGATI MAIDAN</option>
                        <option value="RAJOURI GARDEN">RAJOURI GARDEN</option>
                        <option value="YAMUNA BANK">YAMUNA BANK</option>
                    </select>

                    <input type="submit" value="Submit" />
                </form>
            </div>

        </section>

        <section id="rides" class="table-container">
            <h2>Last 5 Rides</h2>
            <?php
            // Fetch and display the last 5 rides
            $stmt = $mysqli->prepare("SELECT * FROM rides WHERE mcn1 = ? ORDER BY date1 DESC LIMIT 5");
            $stmt->bind_param("s", $mcn);
            $stmt->execute();
            $res = $stmt->get_result();

            echo "<table>
                    <tr>
                        <th>MCN</th>
                        <th>User Name</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Fare</th>
                        <th>Date</th>
                    </tr>";

            while ($row = $res->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['mcn1']) . "</td>
                        <td>" . htmlspecialchars($row['user_name1']) . "</td>
                        <td>" . htmlspecialchars($row['frm1']) . "</td>
                        <td>" . htmlspecialchars($row['to1']) . "</td>
                        <td>₹" . htmlspecialchars($row['fare']) . "</td>
                        <td>" . htmlspecialchars($row['date1']) . "</td>
                    </tr>";
            }
            echo "</table>";

            $stmt->close();
            ?>
        </section>
    </div>

    <footer id="footer" class="footer block background01 animatedParent animateOnce">
        <div class="container">
            <div class="row">
                <div class="footerinner animated fadeIn slow">
                    <div class="logo_bottom">
                        <a href="#"><img class="img-responsive" src="images/logo.png" alt=""></a>
                    </div>
                    <span class="color02 col-xs-12 col-sm-6 col-md-6">Design and Developed by <a href="https://nasa.com" target="_blank">NASA</a></span>
                    <div class="social-icon">
                        <ul>
                            <li><a class="color02" href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a class="color02" href="#"><i class="fa fa-linkedin"></i></a></li>
                            <li><a class="color02" href="#"><i class="fa fa-twitter"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
