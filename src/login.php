<?php require_once 'login_logic.php'; ?>
<!DOCTYPE html>
<html lang="it">
<head>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>LOGIN</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round|Open+Sans">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <!-- CSS personalizzati -->

    <style>
        body {
            font-family: 'Open Sans', Arial, sans-serif;
            background-image: url('design/462749.jpg');
            color: #565656;
        }

        .login-container {
            padding: 40px;
            max-width: 400px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,.05);
        }

        .form-control {
            border-radius: 20px;
        }

        .btn-primary {
            border-radius: 20px;
            padding: 10px 20px;
            font-weight: bold;
        }

        .login-form {
            text-align: left;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
            color: #333;
        }

        .form-label {
            font-weight: bold;
        }
    </style>



</head>


<body>
    <div class="login-container">
        <h1 class="text-center"><b>USER LOGIN</b></h1>
        <?php if (!empty($loginError)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($loginError); ?>
            </div>
        <?php endif; ?>
        <form action="login.php" method="POST" class="login-form">
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" id="email" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" name="password" class="form-control" id="password" required>
            </div>

            <div class="mb-3">
                <input type="submit" value="Login" class="btn btn-primary">
            </div>
        </form>
    </div>
</body>


</html>
