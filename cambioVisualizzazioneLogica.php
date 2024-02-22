<?php
// cambioVisualizzazioneLogica.php
session_start();

require_once __DIR__ . '/root/connect.php';

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

function gestisciCambioVisualizzazione($pdo) {
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
        echo "Operazione completata con successo!";
    } catch (PDOException $e) {
        error_log('Errore nella modifica della visualizzazione: ' . $e->getMessage());
        echo "Errore nella modifica della visualizzazione. Consulta i log per ulteriori dettagli.";
    }
}
?>
