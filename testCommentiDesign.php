<?php
require 'testCommentiLogica.php'; // Includi la logica per ottenere i dati necessari

// Assicurati che il file di connessione al database sia incluso se necessario qui
require_once __DIR__ . '/root/connect.php';

// Recupera i dati dalle variabili di sessione
$testDisponibili = isset($_SESSION['testDisponibili']) ? $_SESSION['testDisponibili'] : [];
$messaggiSelezionati = isset($_SESSION['messaggiSelezionati']) ? $_SESSION['messaggiSelezionati'] : [];

// Dopo il recupero puoi resettare le variabili di sessione se non sono più necessarie
unset($_SESSION['testDisponibili'], $_SESSION['messaggiSelezionati']);

?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizzazione Test e Commenti</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        .test-list-item:hover {
            cursor: pointer;
            background-color: #f0f0f0;
        }

        .test-list-item.active-test {
            background-color: #d4edda;
            /* Bootstrap success color */
        }

        .card,
        .list-group-item {
            border-radius: 20px;
            /* Più rotondità */
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <h2>Test Disponibili</h2>
        <div class="list-group">
            <?php foreach ($testDisponibili as $test) : ?>
                <a href="testCommentiDesign.php?titoloTest=<?= urlencode($test['titolo']) ?>" class="list-group-item list-group-item-action test-list-item <?= (isset($_GET['titoloTest']) && $_GET['titoloTest'] == $test['titolo']) ? 'active-test' : '' ?>">
                    <?= htmlspecialchars($test['titolo']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($messaggiSelezionati)) : ?>
            <h3 class="mt-5">Messaggi per il Test Selezionato</h3>
            <div class="messages">
                <?php foreach ($messaggiSelezionati as $messaggio) : ?>
                    <div class="card mt-3">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($messaggio['titolo']) ?></h5>
                            <p class="card-text"><?= nl2br(htmlspecialchars($messaggio['testo'])) ?></p>
                            <p class="text-muted">Tipo: <?= $messaggio['tipo'] ?></p>
                            <p class="text-muted">Email mittente: <?= !empty($messaggio['emailStudenteMittente']) ? htmlspecialchars($messaggio['emailStudenteMittente']) : (!empty($messaggio['emailDocenteMittente']) ? htmlspecialchars($messaggio['emailDocenteMittente']) : 'Nessuna email disponibile') ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>

</html>