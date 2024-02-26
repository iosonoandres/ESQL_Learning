<?php
// guardaTestDesign.php
session_start();
require_once __DIR__ . '/root/connect.php';
require_once __DIR__ . '/guardaTestLogica.php';


$titoloTest = isset($_GET['titoloTest']) ? $_GET['titoloTest'] : null;
if (!$titoloTest) {
    echo "Test non specificato.";
    exit();
}

$guardaTestLogica = new GuardaTestLogica();
$domande = $guardaTestLogica->getDomandeTest($titoloTest);

function getTipoUtente($email)
{
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
    <title>Guarda Test: <?= htmlspecialchars($titoloTest) ?></title>
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

        .corretta {
            background-color: rgba(76, 175, 80, 0.3);
            /* Colore di sfondo verde con opacità 0.8 */
            color: white;
            /* Testo bianco per una migliore leggibilità */
        }
    </style>

</head>

<body>

    <div class="container">
        <h1 class="mb-4">Guarda Test: <?= htmlspecialchars($titoloTest) ?></h1>
        <?php
        // Fetch the image from the TEST table
        $imageData = $guardaTestLogica->getTestImage($titoloTest);

        // Display the image before the first question
        if (!empty($imageData)) {
            try {
                // Get image information
                $imageInfo = getimagesizefromstring($imageData);

                if ($imageInfo !== false) {
                    $mimeType = $imageInfo['mime'];
                } else {
                    throw new Exception('Failed to determine image type.');
                }
        ?>
                <img src="data:<?= $mimeType ?>;base64,<?= base64_encode($imageData) ?>" alt="Test Image" class="img-fluid mb-4">
            <?php } catch (Exception $ex) { ?>
                <!-- Display error message on the UI -->
                <div class="alert alert-danger" role="alert">
                    Error displaying image: <?= $ex->getMessage() ?>
                </div>
        <?php }
        }
        ?>

        <?php foreach ($domande as $indice => $domanda) : ?>
            <div class="domanda">
                <h5>Domanda <?= $indice + 1 ?>: <?= htmlspecialchars($domanda['descrizione']) ?></h5>
                <?php if (isset($domanda['tipo']) && $domanda['tipo'] === 'codice') : ?>
                    <!-- Display sketch text for questions of type 'codice' -->
                    <?php
                    $sketchText = $guardaTestLogica->getSketchTextForQuestion($domanda['ID'], $titoloTest);
                    if ($sketchText !== null) :
                    ?>
                        <p><strong>Soluzione Corretta:</strong> <?= htmlspecialchars($sketchText) ?></p>
                    <?php endif; ?>
                <?php else : // Domande a risposta chiusa 
                ?>
                    <?php if (isset($domanda['opzioni']) && is_array($domanda['opzioni'])) : ?>
                        <?php foreach ($domanda['opzioni'] as $opzione) : ?>
                            <div class="form-check">
                                <?php
                                $numerazione = $opzione['Numerazione'];
                                $opzioneCorretta = $opzione['opzioneCorretta'];
                                $isChecked = (strpos($opzioneCorretta, $numerazione) !== false) ? ' corretta' : '';
                                ?>
                                <input class="form-check-input" type="radio" name="risposta[<?= $domanda['ID'] ?>]" id="opzione<?= $numerazione ?>" value="<?= $numerazione ?>" disabled <?= $isChecked ? 'checked' : '' ?>>
                                <label class="form-check-label <?= $isChecked ? 'corretta' : '' ?>" for="opzione<?= $numerazione ?>">
                                    <?= htmlspecialchars($opzione['testo']) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>



    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>

</html>