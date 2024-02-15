<?php
session_start();
require_once __DIR__ . '/root/connect.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

function getTipoUtente($email)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT TIPO_ACCOUNT FROM ACCOUNT WHERE EMAIL_ACCOUNT = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetchColumn();
}
$tipoUtente = isset($_SESSION['user']['email']) ? getTipoUtente($_SESSION['user']['email']) : null;



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'creazioneQuesitoLogica.php';
}

function getSelectTest()
{
    global $pdo;
    try {
        // Query per recuperare tutti i test disponibili
        // Modifica questa query in base ai criteri desiderati
        $stmt = $pdo->prepare("SELECT titolo FROM TEST");
        $stmt->execute();
        $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $selectHtml = '<select class="form-control" name="titoloTest" id="titoloTest" required>';
        foreach ($tests as $test) {
            $selectHtml .= '<option value="' . htmlspecialchars($test['titolo']) . '">' . htmlspecialchars($test['titolo']) . '</option>';
        }
        $selectHtml .= '</select>';
        return $selectHtml;
    } catch (PDOException $e) {
        error_log('Errore durante il recupero dei test: ' . $e->getMessage());
        return '<select class="form-control" name="titoloTest" id="titoloTest" required><option value="">Errore nel caricamento dei test</option></select>';
    }
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Inserimento Quesito - Docente</title>
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

        .form-control,
        .btn-primary {
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
        <h1 class="text-center">Inserimento Quesito - Docente</h1>

        <?php if (isset($_SESSION['message'])) : ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['message']; ?>
                <?php unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <?php if ($tipoUtente === 'docente') : ?>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

                <!-- Campo nascosto per specificare l'azione -->
                <input type="hidden" name="azione" value="creaQuesitoChiuso">

                <div class="form-group">
                    <label for="titoloTest">Test associato:</label>
                    <?php echo getSelectTest(); ?>
                </div>

                <div class="form-group">
                    <label for="nomeTabella">Nome Tabella (separato da # per più tabelle):</label>
                    <input type="text" class="form-control" name="nomeTabella" id="nomeTabella" required>
                </div>

                <div class="form-group">
                    <label for="difficolta">Difficoltà:</label>
                    <select class="form-control" name="difficolta" id="difficolta" required>
                        <option value="Basso">Basso</option>
                        <option value="Medio">Medio</option>
                        <option value="Alto">Alto</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="descrizione">Descrizione:</label>
                    <textarea class="form-control" name="descrizione" id="descrizione" required></textarea>
                </div>

                <div class="form-group">
                    <label for="testo">Testo:</label>
                    <textarea class="form-control" name="testo" id="testo" required></textarea>
                </div>

                <div class="form-group">
                    <label for="opzioniCorrette">Opzioni Corrette (separato da ,):</label>
                    <input type="text" class="form-control" name="opzioniCorrette" id="opzioniCorrette" required>
                </div>

                <button type="submit" name="creaQuesitoChiuso" class="btn btn-primary">Crea Quesito Chiuso</button>
            </form>

            <hr>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

                <!-- Campo nascosto per specificare l'azione -->
                <input type="hidden" name="azione" value="creaQuesitoCodice">

                <div class="form-group">
                    <label for="titoloTestCodice">Test associato:</label>
                    <?php echo getSelectTest(); ?>
                </div>

                <div class="form-group">
                    <label for="nomeTabellaCodice">Nome Tabella (separato da # per più tabelle):</label>
                    <input type="text" class="form-control" name="nomeTabella" id="nomeTabellaCodice" required>
                </div>

                <div class="form-group">
                    <label for="difficoltaCodice">Difficoltà:</label>
                    <select class="form-control" name="difficolta" id="difficoltaCodice" required>
                        <option value="Basso">Basso</option>
                        <option value="Medio">Medio</option>
                        <option value="Alto">Alto</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="descrizioneCodice">Descrizione:</label>
                    <textarea class="form-control" name="descrizione" id="descrizioneCodice" required></textarea>
                </div>

                <div class="form-group">
                    <label for="soluzioneCodice">Soluzione (separato da ,):</label>
                    <input type="text" class="form-control" name="soluzione" id="soluzioneCodice" required>
                </div>

                <div class="form-group">
                    <label for="nomeTabSoluzioneCodice">Nome Tabella Soluzione:</label>
                    <input type="text" class="form-control" name="nomeTabSoluzione" id="nomeTabSoluzioneCodice" required>
                </div>

                <button type="submit" name="creaQuesitoCodice" class="btn btn-primary">Crea Quesito di Codice</button>
            </form>

        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>

</html>