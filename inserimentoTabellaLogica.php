<?php
session_start();

require_once __DIR__ . '/root/connect.php'; // Collegamento con il connect




function gestisciCreazioneTabella() {
    // Recupera i dati inviati dal form
    global $pdo;
    $nomeTabella = $_POST['nomeTabella'];
    $emailDocente = $_POST['emailDocente'];
    $metaDati = $_POST['metaDati'];
    $integritaReferenziale = $_POST['integritaReferenziale'];

    // Esegui la procedura per la creazione della tabella
    try {
        $sql = "CALL InserisciTabellaDiEsercizio(?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);        
        $stmt->bindParam(1, $nomeTabella, PDO::PARAM_STR);
        $stmt->bindParam(2, $emailDocente, PDO::PARAM_STR);
        $stmt->bindParam(3, $metaDati, PDO::PARAM_STR);
        $stmt->bindParam(4, $integritaReferenziale, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->closeCursor();

        // Output o redirect a seconda della necessità
        echo "Tabella creata con successo!";
    } catch (PDOException $e) {
        error_log('Errore nella creazione della tabella: ' . $e->getMessage());
        echo "Errore nella creazione della tabella. Consulta i log per ulteriori dettagli.";
    }
}

function gestisciInserimentoRiga() {
    // Recupera i dati inviati dal form
    global $pdo;
    $inputRiga = $_POST['inputRiga'];
    $nomeTabellaRiga = $_POST['nomeTabellaRiga'];
    $emailDocenteRiga = $_POST['emailDocenteRiga'];
    $numAttributiAttesi = $_POST['numAttributiAttesi'];

    // Esegui la procedura per l'inserimento della riga
    try {
        $sql = "CALL InserisciRigaTabellaEsercizio(?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $inputRiga, PDO::PARAM_STR);
        $stmt->bindParam(2, $nomeTabellaRiga, PDO::PARAM_STR);
        $stmt->bindParam(3, $emailDocenteRiga, PDO::PARAM_STR);
        $stmt->bindParam(4, $numAttributiAttesi, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();

        // Output o redirect a seconda della necessità
        echo "Riga inserita con successo!";
    } catch (PDOException $e) {
        error_log('Errore nell\'inserimento della riga: ' . $e->getMessage());
        echo "Errore nell'inserimento della riga. Consulta i log per ulteriori dettagli.";
    }
}
?>
