<?php
// Inizio del file PHP
session_start();
require_once __DIR__ . '/root/connect.php';
require_once __DIR__ . '/visualizzaTestEsitoLogica.php';

if (!isset($_SESSION['user']['email'])) {
    header('Location: login.php');
    exit();
}

$visualizzaTestEsitoLogica = new visualizzaTestEsitoLogica();
$emailStudente = $_SESSION['user']['email'];
$testSvolti = $visualizzaTestEsitoLogica->getTestSvolti($emailStudente);

?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Test Svolti</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Open Sans', Arial, sans-serif;
            background-color: #f7f7f7;
            color: #565656;
        }

        .container {
            padding: 20px;
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
        }

        .test-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .test-item:last-child {
            border-bottom: none;
        }

        .test-title {
            font-size: 18px;
            font-weight: bold;
        }

        .test-date {
            font-size: 14px;
            color: #999;
        }

        .risposta-corretta {
            background-color: lightgreen;
        }

        .risposta-errata {
            background-color: lightyellow;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1 class="text-center">Test Svolti</h1>
        <?php foreach ($testSvolti as $test) : ?>
            <div class="test-item">
                <div class="test-title"><?= htmlspecialchars($test['titolo']) ?></div>
                <div class="test-date">Data: <?= htmlspecialchars($test['data']) ?></div>
                <?php
                $risposte = $guardaTestLogica->getRisposteStudente($emailStudente, $test['titolo']);
                foreach ($risposte as $risposta) {
                    $classeRisposta = $risposta['corretta'] ? "risposta-corretta" : "risposta-errata";
                    echo "<div class='$classeRisposta'>" . htmlspecialchars($risposta['descrizione']) . " - Risposta data: " . htmlspecialchars($risposta['rispostaData']) . "</div>";
                }
                ?>
            </div>
        <?php endforeach; ?>

    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>

</html>