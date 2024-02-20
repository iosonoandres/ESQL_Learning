<?php
session_start();
require_once __DIR__ . '/root/connect.php';
require_once __DIR__ . '/SvolgimentoTestLogica.php';

// Controlla se l'utente è loggato e se è uno studente
if (!isset($_SESSION['user']['email']) || getTipoUtente($_SESSION['user']['email']) != 'studente') {
    header('Location: login.php');
    exit();
}

$titoloTest = isset($_GET['titoloTest']) ? $_GET['titoloTest'] : null;
if (!$titoloTest) {
    echo "Test non specificato.";
    exit();
}

$svolgimentoTestLogica = new SvolgimentoTestLogica();
$domande = $svolgimentoTestLogica->getDomandeTest($titoloTest);


function getTipoUtente($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT TIPO_ACCOUNT FROM ACCOUNT WHERE EMAIL_ACCOUNT = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetchColumn();
}



?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Svolgi Test: <?= htmlspecialchars($titoloTest) ?></title>
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

        .domanda {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #eee;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1 class="mb-4">Svolgi Test: <?= htmlspecialchars($titoloTest) ?></h1>
        <form action="processaRisposteTest.php" method="post">
            <?php foreach ($domande as $indice => $domanda) : ?>
                <div class="domanda">
                    <h5>Domanda <?= $indice + 1 ?>: <?= htmlspecialchars($domanda['descrizione']) ?></h5>
                    <?php if (isset($domanda['tipo']) && $domanda['tipo'] === 'codice') : ?>
                        <textarea name="risposta[<?= $domanda['ID'] ?>]" class="form-control" rows="4" placeholder="Inserisci qui il tuo codice SQL..."></textarea>
                    <?php else : // Domande a risposta chiusa 
                    ?>
                        <?php foreach ($domanda['opzioni'] as $opzione) : ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="risposta[<?= $domanda['ID'] ?>]" id="opzione<?= $opzione['Numerazione'] ?>" value="<?= $opzione['Numerazione'] ?>">
                                <label class="form-check-label" for="opzione<?= $opzione['Numerazione'] ?>">
                                    <?= htmlspecialchars($opzione['testo']) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-primary">Invia Risposte</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>

</html>