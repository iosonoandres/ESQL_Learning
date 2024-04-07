<?php
session_start();
require_once __DIR__ . '/root/connect.php';
require_once __DIR__ . '/SvolgimentoTestLogica.php';

ini_set('error_log', 'C:/xampp/logs/phplog.log');


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
$numeroDomande = count($domande);

$indiceCorrente = isset($_GET['indice']) ? intval($_GET['indice']) : 0;
if ($indiceCorrente >= $numeroDomande) {
    $indiceCorrente = $numeroDomande - 1; // Assicura che l'indice non superi il numero di domande
}
$domandaCorrente = isset($domande[$indiceCorrente]) ? $domande[$indiceCorrente] : null;

// Recupera i dati dell'immagine del test
$imageData = $svolgimentoTestLogica->getTestImage($titoloTest);
$mimeType = 'image/jpeg'; // Imposta un valore predefinito per mimeType
if (!empty($imageData)) {
    $imageInfo = getimagesizefromstring($imageData);
    if ($imageInfo !== false) {
        $mimeType = $imageInfo['mime'];
    } else {
        $imageData = null; // Gestisci il caso in cui l'immagine non sia valida
    }
}

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
    <?php if ($domandaCorrente): ?>
        <form action="processaRispostaSingola.php" method="post">
            <input type="hidden" name="titoloTest" value="<?= htmlspecialchars($titoloTest) ?>">
            <input type="hidden" name="indice" value="<?= $indiceCorrente ?>">
            
            <?php
            // Visualizza l'immagine del test solo prima della prima domanda
            if ($indiceCorrente == 0 && !empty($imageData)) {
                echo '<img src="data:' . htmlspecialchars($mimeType) . ';base64,' . base64_encode($imageData) . '" alt="Test Image" class="img-fluid mb-4">';
            }
            ?>

            <div class="domanda">
                <h5>Domanda <?= $indiceCorrente + 1 ?>: <?= htmlspecialchars($domandaCorrente['descrizione']) ?></h5>
                <?php if ($domandaCorrente['tipo'] === 'codice'): ?>
                    <textarea name="risposta[<?= $domandaCorrente['ID'] ?>]" class="form-control" rows="4" placeholder="Inserisci qui il tuo codice SQL..."></textarea>
                <?php else: ?>
                    <?php foreach ($domandaCorrente['opzioni'] as $opzione): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="risposta[<?= $domandaCorrente['ID'] ?>]" id="opzione<?= $opzione['Numerazione'] ?>" value="<?= $opzione['Numerazione'] ?>">
                            <label class="form-check-label" for="opzione<?= $opzione['Numerazione'] ?>">
                                <?= htmlspecialchars($opzione['testo']) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Bottoni di navigazione -->
            
            <?php if ($indiceCorrente < $numeroDomande - 1): ?>
                <button type="submit" name="azione" value="prossima" class="btn btn-primary">Prossima</button>
            <?php else: ?>
                <button type="submit" name="azione" value="fine" class="btn btn-success">Fine Test</button>
            <?php endif; ?>
        </form>
    <?php else: ?>
        <p>Non ci sono domande disponibili per questo test.</p>
    <?php endif; ?>
</div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
