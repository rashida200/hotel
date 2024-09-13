<?php
session_start();
require "../config/database.php";

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user'])) {
    header("Location: /hotel/index.php");
    exit();
}

// Retrieve session data (replace with your actual logic)
$nom = $_SESSION['user']['nom'] ?? 'Nom';
$prenom = $_SESSION['user']['prenom'] ?? 'Prenom';
$image = $_SESSION['user']['image'] ?? '';

// Fetch total payments for the current month
$stmtPayments = $connexion->prepare("SELECT SUM(montant_total) AS total_payments FROM reservation WHERE MONTH(date_heure_reservation) = MONTH(CURRENT_DATE())");
$stmtPayments->execute();
$reservation = $stmtPayments->fetch(PDO::FETCH_ASSOC);
$totalPayments = $reservation['total_payments'];

// Calculate average revenue per day for the current month
$daysInMonth = date('t');
$stmtRevenue = $connexion->prepare("SELECT SUM(montant_total) AS total_revenue FROM reservation WHERE MONTH(date_heure_reservation) = MONTH(CURRENT_DATE())");
$stmtRevenue->execute();
$revenue = $stmtRevenue->fetch(PDO::FETCH_ASSOC);
$totalRevenue = $revenue['total_revenue'];

$averageRevenue = $totalRevenue / $daysInMonth; // Calculate average revenue per day

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #63101A;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
        }

        .profile {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile img {
            border-radius: 50%;
            width: 100px;
            border: 3px solid white;
        }

        .profile h2 {
            margin-top: 10px;
            font-size: 1.2rem;
        }

        .card-body {
            color: black;
        }

        .sidebar nav {
            width: 100%;
            margin-top: 20px;
            display: flex;
            flex-direction: column;
        }

        .sidebar nav a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px 0;
            transition: background-color 0.3s ease;
        }

        .sidebar nav a:hover {
            background-color: #B72928;
            border-radius: 5px;
        }

        .sidebar nav a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .sidebar a[href="#"] {
            text-align: center;
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            transition: background-color 0.3s ease;
        }

        .sidebar a[href="#"]:hover {
            background-color: #B72928;
            border-radius: 5px;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
            background-color: #FFFFFF;
            overflow-y: auto;
        }

        .header {
            background-color: #c44e4e;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
            color: white;
        }

        .header h1 {
            margin: 0;
            font-size: 2rem;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 1rem;
        }

        .chart-container {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .chart-container h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        .card {
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #B72928;
            color: white;
            border-radius: 5px 5px 0 0;
            padding: 10px;
            text-align: center;
            font-weight: bold;
        }

        .card-body {
            padding: 20px;
        }

        .card-body h5 {
            font-size: 2rem;
            margin: 0;
        }

        .card-body p {
            margin: 0;
            font-size: 1.2rem;
        }

        .sidebar,
        .main-content {
            transition: margin-left 0.3s;
        }

        @media (max-width: 768px) {
            .wrapper {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                margin-bottom: 20px;
            }

            .main-content {
                width: 100%;
            }

            .sidebar nav {
                flex-direction: row;
                justify-content: space-around;
                margin-top: 0;
            }

            .sidebar nav a {
                padding: 10px;
            }

            .sidebar a[href="#"] {
                display: none;
            }

            .sidebar.open {
                margin-left: -250px;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="sidebar">
            <div class="profile">
                <img src="../assets/images/<?= $image ?>" alt="Profile Picture">
                <h2><?= $nom . ' ' . $prenom ?></h2>
            </div>
            <nav>
                <a href="payments.php"><i class="fas fa-money-bill-wave"></i> Payments</a>
            </nav>
            <a href="#" id="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <div class="main-content">
            <div class="header">
                <h1>Welcome, <?= $nom . ' ' . $prenom ?>!</h1>
                <p><?= date('d F, Y') ?></p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container">
                        <h2>Occupancy Status</h2>
                        <canvas id="occupancyChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <h2>Revenue Overview</h2>
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card text-white">
                        <div class="card-header" style="background-color: #63101A;">Total Payments</div>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($totalPayments) ?></h5>
                            <p class="card-text">For this month</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-white">
                        <div class="card-header" style="background-color: #B72928;">Average Revenue</div>
                        <div class="card-body">
                            <h5 class="card-title"><?= number_format($averageRevenue, 2) ?></h5>
                            <p class="card-text">For a day</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('logout-link').addEventListener('click', function(event) {
                event.preventDefault();
                if (confirm("Are you sure you want to log out?")) {
                    window.location.href = '../auth/logout.php';
                }
            });

            // Chart.js setup (replace with actual chart configurations)
            const occupancyChartCtx = document.getElementById('occupancyChart').getContext('2d');
            const occupancyChart = new Chart(occupancyChartCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Occupied', 'Available'],
                    datasets: [{
                        label: 'Occupancy Status',
                        data: [80, 20], // Replace with actual data
                        backgroundColor: ['#c44e4e', '#E3E3E3'],
                        borderWidth: 1
                    }]
                }
            });

            const revenueChartCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(revenueChartCtx, {
                type: 'line',
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                    datasets: [{
                        label: 'Revenue',
                        data: [2000, 2200, 1900, 2100, 2300, 2400], // Replace with actual data
                        borderColor: '#B72928',
                        backgroundColor: 'rgba(183, 41, 40, 0.2)',
                        borderWidth: 1,
                        fill: true
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>
