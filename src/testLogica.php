<?php
// testLogica.php
session_start();

require_once __DIR__ . '/root/connect.php'; // Collegamento con il connect

//includi la classe LoggerMogo
require_once __DIR__ . '../root/LoggerMongo.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["azione"])) {
        $azione = $_POST["azione"];

        switch ($azione) {
            case 'creaTest':
                gestisciCreazioneTest($pdo); // Pass $pdo as a parameter
                break;

                // Altre azioni, se necessario

            default:
                // Azione non supportata
                echo "Azione non supportata.";
        }
    }
}

function getEmailDocente()
{
    // Assuming the session contains the user's email
    return isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : '';
}
$emailDocente = getEmailDocente();

function gestisciCreazioneTest($pdo)
{
    // inizializza il logger
    $logger = new LoggerMongo("mongodb://localhost:27017", "logDB");

    // Recupera i dati inviati dal form

    $titoloTest = $_POST['titoloTest'];
    $dataTest = $_POST['dataTest'];
    $fotoTest = file_get_contents($_FILES['fotoTest']['tmp_name']);
    //$visualizzaRisposte = $_POST['visualizzaRisposte']; DA VERIFICARE

    // Esegui la procedura per la creazione del test
    try {
        $emailDocente = getEmailDocente();
        $sql = "CALL CreazioneNuovoTest(?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $emailDocente, PDO::PARAM_STR);
        $stmt->bindParam(2, $titoloTest, PDO::PARAM_STR);
        $stmt->bindParam(3, $dataTest, PDO::PARAM_STR);
        $stmt->bindParam(4, $fotoTest, PDO::PARAM_LOB);
        $stmt->execute();
        $stmt->closeCursor();

        // Output o redirect a seconda della necessità
        echo '<script>alert("Test creato con successo! Verrai reinderizzato alla dashboard"); window.location.href = "dashboard.php";</script>';
        $logger->logEvent('TestCreation', "Test $titoloTest creato con successo dall'utente $emailDocente");
    } catch (PDOException $e) {
        error_log('Errore nella creazione del test: ' . $e->getMessage());
        echo "Errore nella creazione del test. Consulta i log per ulteriori dettagli.";
        $logger->logEvent('FailedTestCreation', "Tentativo fallito di creazione Test $titoloTest dall'utente $emailDocente");
    }
}
