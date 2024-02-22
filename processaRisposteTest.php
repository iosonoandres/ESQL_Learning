<?php
require_once 'SvolgimentoTestLogica.php';

class ProcessaRisposteTest {
    private $svolgimentoTestLogica;
    private $emailStudente;
    private $titoloTest;

    public function __construct($emailStudente, $titoloTest) {
        $this->svolgimentoTestLogica = new SvolgimentoTestLogica();
        $this->emailStudente = $emailStudente;
        $this->titoloTest = $titoloTest;
        echo "Email Studente: $emailStudente<br>";
        echo "Titolo Test: $titoloTest<br>";
    }

    public function processaRisposte($risposte) {
        $successo = true;
        $messaggiErrore = [];

        foreach ($risposte as $idQuesito => $risposta) {
            try {

                echo "ID Quesito: $idQuesito<br>";
                echo "Risposta: $risposta<br>";

                
                if (is_numeric($risposta)) {
                    // Risposta chiusa
                    $result = $this->svolgimentoTestLogica->inserisciRispostaChiusaStudente(
                        $this->emailStudente,
                        $this->titoloTest,
                        $idQuesito,
                        $risposta
                    );
                    if (!$result) {
                        throw new Exception("Impossibile inserire la risposta chiusa per il quesito $idQuesito.");
                    }
                } else {
                    // Risposta di codice
                    $result = $this->svolgimentoTestLogica->inserisciRispostaCodiceStudente(
                        $this->emailStudente,
                        $this->titoloTest,
                        $idQuesito,
                        $risposta,
                        '' // Assumiamo che il tabOutputStudente non sia necessario o possa essere gestito diversamente
                    );
                    if (!$result) {
                        throw new Exception("Impossibile inserire la risposta di codice per il quesito $idQuesito.");
                    }
                }
            } catch (Exception $e) {
                $successo = false;
                $messaggiErrore[] = $e->getMessage();
            }
        }

        if ($successo) {
            // Tutte le risposte sono state inserite con successo
            return ['successo' => true, 'messaggi' => "Tutte le risposte sono state inviate con successo."];
        } else {
            // Alcune o tutte le risposte non sono state inserite
            return ['successo' => false, 'messaggi' => $messaggiErrore];
        }
    }
}

// Esempio di utilizzo:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();
    if (!isset($_SESSION['user']['email'])) {
        exit("Utente non autorizzato.");
    }
    $emailStudente = $_SESSION['user']['email']; // Assicurati che l'email dello studente sia correttamente memorizzata nella sessione
    $titoloTest = $_POST['titoloTest'];

    $processatore = new ProcessaRisposteTest($emailStudente, $titoloTest);
    $risultato = $processatore->processaRisposte($_POST['risposta']);

    if ($risultato['successo']) {
        // Reindirizza o mostra un messaggio di successo
        echo $risultato['messaggi'];
    } else {
        // Mostra gli errori
        foreach ($risultato['messaggi'] as $errore) {
            echo $errore . "<br>";
        }
    }
}
?>
