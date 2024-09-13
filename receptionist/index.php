<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user'])) {
    header("Location:/hotel/index.php");
    exit();
}

// Retrieve session data (replace with your actual logic)
$nom = $_SESSION['user']['nom'] ?? 'Nom';
$prenom = $_SESSION['user']['prenom'] ?? 'Prenom';
$image = $_SESSION['user']['image'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receptionist Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS for sidebar and charts -->
    <style>
        body {
            background-color: #C4AD8E;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .wrapper {
            display: flex;
            width: 100%;
            background-color: #FFFFFF;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            height: 100vh;
            margin: 0;
        }
        .sidebar {
            background-color: #63101A;
            width: 250px;
            padding: 20px;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar .profile {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar .profile img {
            border-radius: 50%;
            width: 100px;
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
        }

        .header {
            background-color: #c44e4e;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
        }

        .chart-container {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .chart-container canvas {
            width: 100%;
            max-width: 400px;
            height: auto;
        }

        .chart-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .card {
            border-radius: 10px;
            margin-bottom: 20px;
        }

        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <div class="profile">
                <img src="../assets/images/<?= htmlspecialchars($image) ?>" alt="Profile Picture">
                <h2><?= htmlspecialchars($nom . ' ' . $prenom) ?></h2>
            </div>
            <nav>
                <a href="../receptionist/reservations/list.php"><i class="fas fa-book"></i> Reservation Management</a>
                <a href="../receptionist/rooms/list.php"><i class="fas fa-sign-in-alt"></i> Room Management</a>
                <a href="../receptionist/clients/list.php"><i class="fas fa-users"></i> Client Management</a>
                <a href="../receptionist/configuration/index.php"><i class="fas fa-cogs"></i> Configuration Management</a> <!-- New Button -->
            </nav>
            <a href="#" id="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <div class="main-content">
            <div class="header">
                <h1>Welcome, <?= htmlspecialchars($nom . ' ' . $prenom) ?>!</h1>
                <p><?= date('d F, Y') ?></p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container">
                        <h2>Reservations</h2>
                        <canvas id="reservationChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <h2>Check-ins and Check-outs</h2>
                        <canvas id="checkInOutChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card text-white" style="background-color: #63101A;">
                        <div class="card-header">Total Reservations</div>
                        <div class="card-body">
                            <h5 class="card-title">120</h5>
                            <p class="card-text">For this month</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-white" style="background-color: #B72928;">
                        <div class="card-header">Total Check-ins</div>
                        <div class="card-body">
                            <h5 class="card-title">80</h5>
                            <p class="card-text">For this month</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Logout confirmation
            document.getElementById('logout-link').addEventListener('click', function(event) {
                event.preventDefault();

                // Show a confirmation dialog
                if (confirm("Are you sure you want to log out?")) {
                    // Redirect to logout.php if user confirms
                    window.location.href = '../auth/logout.php';
                }
            });

            // Chart.js code for Reservations
            const ctx1 = document.getElementById('reservationChart').getContext('2d');
            const reservationChart = new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    datasets: [{
                        label: 'Reservations',
                        data: [30, 25, 35, 30],
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
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

            // Chart.js code for Check-ins and Check-outs
            const ctx2 = document.getElementById('checkInOutChart').getContext('2d');
            const checkInOutChart = new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    datasets: [
                        {
                            label: 'Check-ins',
                            data: [20, 30, 25, 35],
                            borderColor: 'rgba(54, 162, 235, 1)',
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderWidth: 1,
                            fill: true
                        },
                        {
                            label: 'Check-outs',
                            data: [15, 25, 20, 30],
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 1,
                            fill: true
                        }
                    ]
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
