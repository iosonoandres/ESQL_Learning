<?php 
// testDesign.php
session_start();
require_once __DIR__ . '/root/connect.php';

function getTipoUtente($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT TIPO_ACCOUNT FROM ACCOUNT WHERE EMAIL_ACCOUNT = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetchColumn();
}
$tipoUtente = isset($_SESSION['user']['email']) ? getTipoUtente($_SESSION['user']['email']) : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'testLogica.php';
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Inserimento Test</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Open Sans', Arial, sans-serif;
            background-color: #f7f7f7;
            color: #565656;
        }
        .container {
            padding: 40px;
            max-width: 700px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, .05);
        }
        .form-control, .btn-primary {
            border-radius: 20px;
        }
        .btn-primary {
            padding: 10px 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center">Inserimento Test</h1>
    <form id="creaTestForm" method="POST" action="testLogica.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="titoloTest">Titolo del Test:</label>
            <input type="text" class="form-control" name="titoloTest" id="titoloTest" required>
        </div>

        <div class="form-group">
            <label for="dataTest">Data del Test:</label>
            <input type="date" class="form-control" name="dataTest" id="dataTest" required>
        </div>

        <div class="form-group">
            <label for="fotoTest">Foto del Test (BLOB):</label>
            <input type="file" class="form-control" name="fotoTest" id="fotoTest" accept="image/*" required>
        </div>

        <button type="submit" name="azione" value="creaTest" class="btn btn-primary">Crea Test</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
