<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 50px;
        }
        .container {
            max-width: 600px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .btn {
            margin: 10px 0;
        }
        .btn-primary {
            background-color: #C44E4E;
            border-color: #C44E4E;
        }
        .btn-primary:hover {
            background-color: #b04343;
            border-color: #b04343;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Configuration Dashboard</h2>
        <div class="mb-3">
            <a href="../capacities/list.php" class="btn btn-primary btn-block">
                <i class="fas fa-users"></i> Capacity Management
            </a>
            <a href="../tariffs/list.php" class="btn btn-primary btn-block">
                <i class="fas fa-dollar-sign"></i> Rate Management
            </a>
            <a href="../types/list.php" class="btn btn-primary btn-block">
                <i class="fas fa-bed"></i> Type Management
            </a>
        </div>
        <a href="../index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- JavaScript libraries (Font Awesome, Bootstrap, jQuery) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
