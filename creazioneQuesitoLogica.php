<?php
// creazioneQuesitoLogica.php

//includi la classe LoggerMogo
require_once __DIR__ . '../root/LoggerMongo.php';


class CreazioneQuesitoLogica
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    function creaQuesitoChiuso($titoloTest, $nomeTabella, $difficolta, $descrizione, $testo, $opzioniCorrette, $emaildocente)
    {
        global $pdo;

        // inizializza il logger
        $logger = new LoggerMongo("mongodb://localhost:27017", "logDB");

        try {
            // La stored procedure è stata aggiornata per accettare i nuovi parametri.
            // Nota: assicurati che i valori inviati alla stored procedure siano correttamente formati.
            // Ad esempio, $nomeTabella potrebbe essere una stringa del tipo "tavolo#cane", e $opzioniCorrette "1,2"
            $stmt = $pdo->prepare("CALL dbESQL.creazioneQuesitoChiusoConRisposte(?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$titoloTest, $nomeTabella, $difficolta, $descrizione, $testo, $opzioniCorrette, $emaildocente]);

            // Gestisci il successo dell'operazione, ad esempio salvando un messaggio in sessione o reindirizzando l'utente
            $_SESSION['message'] = 'Quesito chiuso creato con successo!';
            $logger->logEvent('QuestionCreation', "Nuovo quesito chiuso inserito su $titoloTest");

            // Reindirizzamento opzionale a una pagina di successo
            // header('Location: pagina_di_successo.php');
            // exit();

        } catch (PDOException $e) {
            // Gestisci l'errore, ad esempio salvando un messaggio di errore in sessione o reindirizzando l'utente
            $_SESSION['error'] = 'Errore nella creazione del quesito chiuso: ' . $e->getMessage();
            $logger->logEvent('FailedQuestionCreation', "tentativo fallito inserimento quesito chiuso su $titoloTest");

            // Reindirizzamento opzionale a una pagina di errore
            // header('Location: pagina_di_errore.php');
            // exit();
        }
    }
    
    public function creaQuesitoCodice($titoloTest, $nomeTabella, $difficolta, $descrizione, $soluzione, $nomeTabSoluzione, $emaildocente)
    {
        // inizializza il logger
        $logger = new LoggerMongo("mongodb://localhost:27017", "logDB");

        try {
            // Controllo se esiste già una tabella con lo stesso nome
            $stmtCheckTable = $this->pdo->prepare("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ?");
            $stmtCheckTable->execute([$nomeTabSoluzione]);
            $tableExists = $stmtCheckTable->fetchColumn();

            // Se esiste una tabella con lo stesso nome, restituisce un messaggio di errore
            if ($tableExists > 0) {
                echo "Esiste già una tabella di soluzione con lo stesso nome: scegli un nome diverso.";
                return false;
            }

            // Altrimenti, procedi con la creazione del quesito di codice
            $stmt = $this->pdo->prepare("CALL dbESQL.creazioneQuesitoCodiceConRisposte(?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$titoloTest, $nomeTabella, $difficolta, $descrizione, $soluzione, $nomeTabSoluzione, $emaildocente]);
            $logger->logEvent('QuestionCreation', "Nuovo quesito di codice inserito su $titoloTest");
            return true;
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();

            // Verifica se il messaggio di errore contiene la stringa specifica
            if (strpos($errorMessage, "Esiste già una tabella di soluzione con lo stesso nome") !== false) {
                // Messaggio personalizzato da mostrare nell'UI
                echo "Esiste già una tabella di soluzione con lo stesso nome: scegli un nome diverso.";
            } else {
                // Altrimenti, gestisci l'errore come di consueto
                error_log('Errore nella creazione del quesito di codice: ' . $errorMessage);
                $logger->logEvent('FailedQuestionCreation', "tentativo fallito inserimento quesito di codice su $titoloTest");
            }

            return false;
        }
    }
}

// Utilizzo della classe CreazioneQuesitoLogica
$creazioneQuesitoLogica = new CreazioneQuesitoLogica();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Assumi che i dati siano stati validati e sanificati
    $titoloTest = $_POST['titoloTest'];
    $nomeTabella = $_POST['nomeTabella'];
    $difficolta = $_POST['difficolta'];
    $descrizione = $_POST['descrizione'];
    $emailDocente = getEmailDocente();

    if (isset($_POST['creaQuesitoChiuso'])) {
        // Estrai gli altri parametri dal POST
        $testo = $_POST['testo'];
        $opzioniCorrette = $_POST['opzioniCorrette'];
        $opzioniNonCorrette = $_POST['opzioniNonCorrette']; // Nuovo campo aggiunto

        // Chiamata al metodo della classe, non alla funzione globale
        $risultato = $creazioneQuesitoLogica->creaQuesitoChiuso($titoloTest, $nomeTabella, $difficolta, $descrizione, $testo, $opzioniCorrette, $emailDocente, $opzioniNonCorrette);

        // Gestisci il risultato dell'operazione...
        if ($risultato) {
            $_SESSION['message'] = 'Quesito chiuso creato con successo!';
        } else {
            $_SESSION['error'] = 'Errore nella creazione del quesito chiuso.';
        }
    } elseif (isset($_POST['creaQuesitoCodice'])) {
        $soluzione = $_POST['soluzione'];
        $nomeTabSoluzione = $_POST['nomeTabSoluzione'];
        $successo = $creazioneQuesitoLogica->creaQuesitoCodice($titoloTest, $nomeTabella, $difficolta, $descrizione, $soluzione, $nomeTabSoluzione, $emailDocente);

        if ($successo) {
            $_SESSION['message'] = 'Quesito di codice creato con successo!';
        } else {
            $_SESSION['error'] = 'Errore nella creazione del quesito di codice.';
        }
    }

    // // Redirect a seconda dell'esito
    // header('Location: ' . ($_SESSION['message'] ? 'banana.php' : 'pagina_di_errore.php'));
    // exit();
}
