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

function getEmailDocente()
{
    // Assuming the session contains the user's email
    return isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : '';
}

// pesca dell'emailDocente 
$emailDocente = getEmailDocente();

// Funzione che recupera i test disponibili per il menu a tendina filtrati per emailDocente
function getSelectTest($emailDocente)
{
    global $pdo;
    try {
        // Query per recuperare i test disponibili per l'emailDocente specificato
        $stmt = $pdo->prepare("SELECT titolo FROM TEST WHERE emailDocente = :emailDocente");
        $stmt->bindParam(':emailDocente', $emailDocente, PDO::PARAM_STR);
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
    <script>
        // Funzione per mostrare o nascondere il campo in base alla selezione
        function toggleTestField() {
            var select = document.getElementById('titoloTest');
            var selectedValue = select.options[select.selectedIndex].value;

            // Imposta l'ID del campo che vuoi mostrare o nascondere
            var campoDaMostrareONascondere = 'campoDaMostrare';

            // Mostriamo o nascondiamo il campo in base al valore selezionato
            if (selectedValue === 'valoreDesiderato') {
                document.getElementById(campoDaMostrareONascondere).style.display = 'block';
            } else {
                document.getElementById(campoDaMostrareONascondere).style.display = 'none';
            }
        }
    </script>
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
                    <?php echo getSelectTest($emailDocente); ?>
                </div>

                <div class="form-group">
                    <label for="nomeTabella">Nome Tabella (separato da # per più tabelle):</label>
                    <textarea class="form-control" name="nomeTabella" id="nomeTabella" required></textarea>
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
                    <label for="testo">Scrivere opzioni Test (separate da #):</label>
                    <textarea class="form-control" name="testo" id="testo" required></textarea>
                </div>

                <div class="form-group">
                    <label for="opzioniCorrette">Opzioni Corrette (separato da ,):</label>
                    <input type="text" class="form-control" name="opzioniCorrette" id="opzioniCorrette" required>
                </div>

                <!-- Campo aggiunto per le opzioni non corrette -->
                <div class="form-group">
                    <label for="opzioniNonCorrette">Opzioni Non Corrette (IN NUMERI separato da ,):</label>
                    <input type="text" class="form-control" name="opzioniNonCorrette" id="opzioniNonCorrette" required>
                </div>

                <button type="submit" name="creaQuesitoChiuso" class="btn btn-primary">Crea Quesito Chiuso</button>
            </form>

            <hr>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

                <!-- Campo nascosto per specificare l'azione -->
                <input type="hidden" name="azione" value="creaQuesitoCodice">

                <div class="form-group">
                    <label for="titoloTestCodice">Test associato:</label>
                    <?php echo getSelectTest($emailDocente); ?>
                </div>

                <div class="form-group">
                    <label for="nomeTabella">Nome Tabella (separato da # per più tabelle):</label>
                    <textarea class="form-control" name="nomeTabella" id="nomeTabella" required></textarea>
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

        <?php elseif ($tipoUtente === 'studente') : ?>
            <style>
                body {
                    background-image: url('design/divietovero.jpg');
                    background-size: cover;
                    background: position 100px;
                    ;
                }
            </style>
            <div class="alert alert-danger" role="alert">
                Non hai i permessi per accedere a questa pagina.
            </div>
            <!-- Qui puoi includere altri contenuti HTML che vuoi mostrare allo studente -->
        <?php endif ?>


    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>

</html>