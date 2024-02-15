<?php
session_start();

// Assicurati che il file di connessione al database sia incluso
require_once __DIR__ . '/root/connect.php';

function getTipoUtente($email)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT TIPO_ACCOUNT FROM ACCOUNT WHERE EMAIL_ACCOUNT = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetchColumn();
}

if (!isset($_SESSION['user']['email'])) {
    header('Location: login.php');
    exit();
}

$tipoUtente = getTipoUtente($_SESSION['user']['email']);

function creaQuesitoChiuso($titoloTest, $nomeTabella, $difficolta, $descrizione, $testo, $opzioniCorrette)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("CALL dbESQL.creazioneQuesitoChiusoConRisposte(?, ?, ?, ?, ?, ?)");
        $stmt->execute([$titoloTest, $nomeTabella, $difficolta, $descrizione, $testo, $opzioniCorrette]);

        // Se la procedura è stata eseguita correttamente, esegui le azioni desiderate
        // ad esempio, reindirizza l'utente o mostra un messaggio di successo.
        
        // Esempio:
        // $_SESSION['message'] = 'Quesito chiuso creato con successo!';
        // header('Location: pagina_di_successo.php');
        // exit();

    } catch (PDOException $e) {
        // Log dell'errore e segnalazione all'utente
        error_log('Errore nella creazione del quesito chiuso: ' . $e->getMessage());
        $_SESSION['error'] = 'Errore nella creazione del quesito chiuso. Si prega di riprovare.';
        header('Location: pagina_di_errore.php');
        exit();
    }
}

function creaQuesitoCodice($titoloTest, $nomeTabella, $difficolta, $descrizione, $soluzione, $nomeTabSoluzione)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("CALL dbESQL.creazioneQuesitoCodiceConRisposte(?, ?, ?, ?, ?, ?)");
        $stmt->execute([$titoloTest, $nomeTabella, $difficolta, $descrizione, $soluzione, $nomeTabSoluzione]);

        // Se la procedura è stata eseguita correttamente, esegui le azioni desiderate
        // ad esempio, reindirizza l'utente o mostra un messaggio di successo.

        // Esempio:
        // $_SESSION['message'] = 'Quesito di codice creato con successo!';
        // header('Location: pagina_di_successo.php');
        // exit();

        $stmt->closeCursor(); // Chiudi il cursore per liberare le risorse

        // Esegui ulteriori azioni o visualizza un messaggio di successo
        $_SESSION['message'] = 'Quesito di codice creato con successo!';
        header('Location: pagina_di_successo.php');
        exit();

    } catch (PDOException $e) {
        // Log dell'errore e segnalazione all'utente
        error_log('Errore nella creazione del quesito di codice: ' . $e->getMessage());
        $_SESSION['error'] = 'Errore nella creazione del quesito di codice. Si prega di riprovare.';
        header('Location: pagina_di_errore.php');
        exit();
    }
}


