<?php
require_once __DIR__ . '/root/connect.php'; // Collegamento con il connect

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

function gestisciCreazioneTest($pdo) {
    // Recupera i dati inviati dal form
    $emailDocente = $_POST['emailDocente'];
    $titoloTest = $_POST['titoloTest'];
    $dataTest = $_POST['dataTest'];
    $fotoTest = file_get_contents($_FILES['fotoTest']['tmp_name']);
    $visualizzaRisposte = $_POST['visualizzaRisposte'];

    // Esegui la procedura per la creazione del test
    try {
        $sql = "CALL CreazioneNuovoTest(?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $emailDocente, PDO::PARAM_STR);
        $stmt->bindParam(2, $titoloTest, PDO::PARAM_STR);
        $stmt->bindParam(3, $dataTest, PDO::PARAM_STR);
        $stmt->bindParam(4, $fotoTest, PDO::PARAM_LOB);
        $stmt->bindParam(5, $visualizzaRisposte, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();

        // Output o redirect a seconda della necessità
        echo "Test creato con successo!";
    } catch (PDOException $e) {
        error_log('Errore nella creazione del test: ' . $e->getMessage());
        echo "Errore nella creazione del test. Consulta i log per ulteriori dettagli.";
    }
}
?>
