<?php
// inserimentoMessaggioLogica.php
session_start();

// Assicurati che il file di connessione al database sia incluso
require_once __DIR__ . '/root/connect.php';

//includi la classe LoggerMogo
require_once __DIR__ . '../root/LoggerMongo.php';


ini_set('display_errors', 1);
error_reporting(E_ALL);





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

if (isset($_POST['azione'])) {
    // inizializza il logger
    $logger = new LoggerMongo("mongodb://localhost:27017", "logDB");


    if ($_POST['azione'] == 'inserisciCommento' && $tipoUtente === 'studente') {
        $titolo = $_POST['titolo']; // Assumi che questo campo sia presente nel form per gli studenti.
        $testo = $_POST['testo'];
        $titoloTest = $_POST['titoloTest'];
        $emailStudente = $_SESSION['user']['email']; // Email dello studente dalla sessione.
        $emailDocenteDestinatario = $_POST['emailDocente']; // Email del docente destinatario dal form.

        try {
            $stmt = $pdo->prepare("CALL dbESQL.InserisciMessaggioStudente(?, ?, ?, ?, ?)");
            $stmt->execute([
                $titolo,
                $testo,
                $titoloTest,
                $emailStudente,
                $emailDocenteDestinatario
            ]);

            // Se l'inserimento è riuscito, imposta un messaggio di successo.
            $_SESSION['message'] = 'Commento inserito correttamente!';
            $logger->logEvent('CommentInsertion', "Commento inserito da $emailStudente su test $titoloTest");
            header('Location: inserimentoMessaggioDesign.php'); // Reindirizza per evitare inserimenti multipli al refresh.
            exit();
        } catch (PDOException $e) {
            // Log dell'errore e segnalazione all'utente
            error_log('Errore nell\'inserimento del commento: ' . $e->getMessage());
            $_SESSION['error'] = 'Errore nell\'inserimento del commento. Si prega di riprovare.';
            $logger->logEvent('FailedCommentInsertion', "Tentativo Fallito Inserimento Commento da $emailStudente su test $titoloTest");
            header('Location: inserimentoMessaggioDesign.php');
            exit();
        }
    }

    // Gestione dell'invio del quesito da parte del docente
    if ($_POST['azione'] == 'inserisciQuesito' && $tipoUtente === 'docente') {
        $titolo = $_POST['titolo'];
        $testo = $_POST['testo'];
        $titoloTest = $_POST['titoloTest']; // Assumo che anche il docente selezioni un test associato
        $emailDocente = $_SESSION['user']['email']; // Email del docente dalla sessione

        try {
            $stmt = $pdo->prepare("CALL dbESQL.InserisciMessaggioDocente(?, ?, ?, ?)");
            $stmt->execute([
                $titolo,
                $testo,
                $titoloTest,
                $emailDocente
            ]);

            $_SESSION['message'] = 'Risposta inserita correttamente!';
            $logger->logEvent('ReplyInsertion', "Risposta inserita da $emailDocente su test $titoloTest");
            header('Location: inserimentoMessaggioDesign.php');
            exit();
        } catch (PDOException $e) {
            error_log('Errore nell\'inserimento del quesito: ' . $e->getMessage());
            $_SESSION['error'] = 'Errore nell\'inserimento della risposta. Si prega di riprovare.';
            $logger->logEvent('FailedReplyInsertion', "Tentativo fallito inserimento messaggio da $emailDocente su test $titoloTest");
            header('Location: inserimentoMessaggioDesign.php');
            exit();
        }
    }
}





// Funzione per recuperare i messaggi di un test specifico
function getMessaggiTest($titoloTest)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT testo FROM MESSAGGI WHERE titoloTest = :titoloTest");
    $stmt->execute(['titoloTest' => $titoloTest]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
