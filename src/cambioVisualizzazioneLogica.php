<?php
// cambioVisualizzazioneLogica.php
session_start();

require_once __DIR__ . '/root/connect.php';

//includi la classe LoggerMogo
require_once __DIR__ . '../root/LoggerMongo.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["azione"])) {
        $azione = $_POST["azione"];

        switch ($azione) {
            case 'cambiaVisualizzazione':
                gestisciCambioVisualizzazione($pdo);
                break;

                // Altre azioni, se necessario

            default:
                // Azione non supportata
                echo "Azione non supportata.";
        }
    }
}

function gestisciCambioVisualizzazione($pdo)
{
    // inizializza il logger
    $logger = new LoggerMongo("mongodb://localhost:27017", "logDB");

    $titoloTest = $_POST['titoloTest'];
    $abilitaVisualizzazione = $_POST['abilitaVisualizzazione'];

    try {
        // Chiama la procedura dbESQL.VisualizzazioneRisposte
        $sql = "CALL dbESQL.VisualizzazioneRisposte(?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $titoloTest, PDO::PARAM_STR);
        $stmt->bindParam(2, $abilitaVisualizzazione, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();

        // Output o redirect a seconda della necessità
        $logger->logEvent('ChangeTestView', "Visualizzazione test $titoloTest settato a " . ($abilitaVisualizzazione == 1 ? 'true' : 'false'));
        echo '<script>alert("Hai cambiato correttamente lo stato del test! Verrai reinderizzato alla dashboard"); window.location.href = "dashboard.php";</script>';

    } catch (PDOException $e) {
        error_log('Errore nella modifica della visualizzazione: ' . $e->getMessage());
        echo "Errore nella modifica della visualizzazione. Consulta i log per ulteriori dettagli.";
        $logger->logEvent(
            'FailedChangeTestView',
            "Errore settaggio visualizzazione test $titoloTest a " . ($abilitaVisualizzazione == 1 ? 'true' : 'false')
        );
    }
}
