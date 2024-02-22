<?php
// creazioneQuesitoLogica.php
class CreazioneQuesitoLogica {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    function creaQuesitoChiuso($titoloTest, $nomeTabella, $difficolta, $descrizione, $testo, $opzioniCorrette) {
        global $pdo;

        try {
            // La stored procedure è stata aggiornata per accettare i nuovi parametri.
            // Nota: assicurati che i valori inviati alla stored procedure siano correttamente formati.
            // Ad esempio, $nomeTabella potrebbe essere una stringa del tipo "tavolo#cane", e $opzioniCorrette "1,2"
            $stmt = $pdo->prepare("CALL dbESQL.creazioneQuesitoChiusoConRisposte(?, ?, ?, ?, ?, ?)");
            $stmt->execute([$titoloTest, $nomeTabella, $difficolta, $descrizione, $testo, $opzioniCorrette]);

            // Gestisci il successo dell'operazione, ad esempio salvando un messaggio in sessione o reindirizzando l'utente
            $_SESSION['message'] = 'Quesito chiuso creato con successo!';
            // Reindirizzamento opzionale a una pagina di successo
            // header('Location: pagina_di_successo.php');
            // exit();

        } catch (PDOException $e) {
            // Gestisci l'errore, ad esempio salvando un messaggio di errore in sessione o reindirizzando l'utente
            $_SESSION['error'] = 'Errore nella creazione del quesito chiuso: ' . $e->getMessage();
            // Reindirizzamento opzionale a una pagina di errore
            // header('Location: pagina_di_errore.php');
            // exit();
        }
    }


    public function creaQuesitoCodice($titoloTest, $nomeTabella, $difficolta, $descrizione, $soluzione, $nomeTabSoluzione) {
        try {
            $stmt = $this->pdo->prepare("CALL dbESQL.creazioneQuesitoCodiceConRisposte(?, ?, ?, ?, ?, ?)");
            $stmt->execute([$titoloTest, $nomeTabella, $difficolta, $descrizione, $soluzione, $nomeTabSoluzione]);
            return true;
        } catch (PDOException $e) {
            error_log('Errore nella creazione del quesito di codice: ' . $e->getMessage());
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

    if (isset($_POST['creaQuesitoChiuso'])) {
        // Estrai gli altri parametri dal POST
        $testo = $_POST['testo'];
        $opzioniCorrette = $_POST['opzioniCorrette'];
        $opzioniNonCorrette = $_POST['opzioniNonCorrette']; // Nuovo campo aggiunto

        // Chiamata al metodo della classe, non alla funzione globale
        $risultato = $creazioneQuesitoLogica->creaQuesitoChiuso($titoloTest, $nomeTabella, $difficolta, $descrizione, $testo, $opzioniCorrette, $opzioniNonCorrette);

        // Gestisci il risultato dell'operazione...
        if ($risultato) {
            $_SESSION['message'] = 'Quesito chiuso creato con successo!';
        } else {
            $_SESSION['error'] = 'Errore nella creazione del quesito chiuso.';
        }
    }
     elseif (isset($_POST['creaQuesitoCodice'])) {
        $soluzione = $_POST['soluzione'];
        $nomeTabSoluzione = $_POST['nomeTabSoluzione'];
        $successo = $creazioneQuesitoLogica->creaQuesitoCodice($titoloTest, $nomeTabella, $difficolta, $descrizione, $soluzione, $nomeTabSoluzione);

        if ($successo) {
            $_SESSION['message'] = 'Quesito di codice creato con successo!';
        } else {
            $_SESSION['error'] = 'Errore nella creazione del quesito di codice.';
        }
    }

    // Redirect a seconda dell'esito
    header('Location: ' . ($_SESSION['message'] ? 'banana.php' : 'pagina_di_errore.php'));
    exit();
}
?>