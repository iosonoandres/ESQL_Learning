<?php
// guardaTestLogica.php
require_once __DIR__ . '/root/connect.php';

class visualizzaTestEsitoLogica
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getTestDisponibili($emailStudente)
    {
        $testDisponibili = [];
        try {
            $stmt = $this->pdo->prepare("SELECT t.titolo, t.data 
                                         FROM TEST t
                                         LEFT JOIN SVOLGIMENTO s ON t.titolo = s.titoloTest AND s.emailStudente = :emailStudente
                                         WHERE s.emailStudente IS NULL OR s.stato = 'Concluso'");
            $stmt->bindParam(':emailStudente', $emailStudente, PDO::PARAM_STR);
            $stmt->execute();
            $testDisponibili = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Errore nel recupero dei test disponibili: " . $e->getMessage();
        }
        return $testDisponibili;
    }

    // Metodo per recuperare il nome e il cognome dello studente
    public function getNomeCognomeStudente($emailStudente) {
        try {
            $stmt = $this->pdo->prepare("SELECT nome, cognome FROM UTENTE WHERE email = :email");
            $stmt->bindParam(':email', $emailStudente, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); // Restituisce un array associativo con 'nome' e 'cognome'
        } catch (PDOException $e) {
            echo "Errore nel recupero del nome e cognome dello studente: " . $e->getMessage();
            return null;
        }
    }

    // Nuovo metodo per recuperare i test svolti dall'utente
    public function getTestSvolti($emailStudente) {
        $testSvolti = [];
        try {
            $stmt = $this->pdo->prepare("SELECT t.titolo, t.data, s.stato 
                                         FROM TEST t
                                         JOIN SVOLGIMENTO s ON t.titolo = s.titoloTest
                                         WHERE s.emailStudente = :emailStudente AND s.stato = 'Concluso'");
            $stmt->bindParam(':emailStudente', $emailStudente, PDO::PARAM_STR);
            $stmt->execute();
            $testSvolti = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Errore nel recupero dei test svolti: " . $e->getMessage();
        }
        return $testSvolti;
    }

    public function getRisposteStudente($emailStudente, $titoloTest) {
        $risposte = [];
        try {
            // Questa query aggrega le informazioni dalle risposte chiuse e di codice,
            // unendole con i quesiti corrispondenti e recuperando nome e cognome dello studente.
            $stmt = $this->pdo->prepare("
                SELECT 
                    q.ID AS quesitoID, 
                    q.descrizione AS quesitoDescrizione, 
                    CASE 
                        WHEN rc.data IS NOT NULL THEN rc.data
                        WHEN rcod.data IS NOT NULL THEN rcod.data
                    END AS rispostaData,
                    CASE 
                        WHEN rc.esito IS NOT NULL THEN rc.esito
                        WHEN rcod.esito IS NOT NULL THEN rcod.esito
                    END AS rispostaEsito,
                    u.nome AS studenteNome, 
                    u.cognome AS studenteCognome
                FROM QUESITO q
                LEFT JOIN RISPOSTA_CHIUSA rc ON q.ID = rc.IDQuesito AND q.titoloTest = rc.titoloTest
                LEFT JOIN RISPOSTA_CODICE rcod ON q.ID = rcod.IDQuesito AND q.titoloTest = rcod.titoloTest
                INNER JOIN UTENTE u ON rc.emailStudente = u.email OR rcod.emailStudente = u.email
                WHERE (rc.emailStudente = :emailStudente OR rcod.emailStudente = :emailStudente) 
                AND q.titoloTest = :titoloTest
                GROUP BY q.ID
            ");
            $stmt->bindParam(':emailStudente', $emailStudente, PDO::PARAM_STR);
            $stmt->bindParam(':titoloTest', $titoloTest, PDO::PARAM_STR);
            $stmt->execute();
            $risposte = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Errore nel recupero delle risposte dello studente: " . $e->getMessage();
        }
        return $risposte;
    }
    
    
    
    
    
    
    



    public function getTestImage($titoloTest)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT foto FROM TEST WHERE titolo = :titoloTest");
            $stmt->bindParam(':titoloTest', $titoloTest, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($result) && isset($result['foto'])) {
                return $result['foto'];
            }
        } catch (PDOException $e) {
            echo "Errore nel recupero dell'immagine del test: " . $e->getMessage();
        }

        return null;
    }

    public function getDomandeTest($titoloTest)
    {
        try {
            // Verifica se il test consente la visualizzazione delle risposte
            $stmtVisualizzaRisposte = $this->pdo->prepare("SELECT visualizzaRisposte FROM TEST WHERE titolo = :titoloTest");
            $stmtVisualizzaRisposte->bindParam(':titoloTest', $titoloTest, PDO::PARAM_STR);
            $stmtVisualizzaRisposte->execute();
            $visualizzaRisposte = $stmtVisualizzaRisposte->fetchColumn();

            // Se visualizzaRisposte è uguale a 0, mostra un messaggio informativo e interrompi l'esecuzione
            if ($visualizzaRisposte == 0) {
                echo "Il docente non ha abilitato la visualizzazione di questo test. Controllare più tardi.";
                return []; // Ritorna un array vuoto, poiché non ci sono domande da visualizzare
            }

            // Seleziona tutte le domande del test
            $stmt = $this->pdo->prepare("SELECT q.ID, q.descrizione, q.difficoltà, 'codice' AS tipo 
                                         FROM QUESITO q
                                         JOIN QUESITO_DI_CODICE qc ON q.ID = qc.ID AND q.titoloTest = qc.titoloTest
                                         WHERE q.titoloTest = :titoloTest
                                         UNION ALL
                                         SELECT q.ID, q.descrizione, q.difficoltà, 'chiusa' AS tipo 
                                         FROM QUESITO q
                                         JOIN QUESITO_A_RISPOSTA_CHIUSA qrc ON q.ID = qrc.ID AND q.titoloTest = qrc.titoloTest
                                         WHERE q.titoloTest = :titoloTest");
            $stmt->bindParam(':titoloTest', $titoloTest, PDO::PARAM_STR);
            $stmt->execute();
            $domande = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Per ciascuna domanda a risposta chiusa, recupera le opzioni disponibili
            foreach ($domande as $key => $domanda) {
                if ($domanda['tipo'] == 'chiusa') {
                    $stmtOpzioni = $this->pdo->prepare("SELECT Numerazione, testo, opzioneCorretta 
                                                        FROM OPZIONE 
                                                        WHERE idQuesitoChiusa = :idQuesito AND titoloTest = :titoloTest");
                    $stmtOpzioni->bindParam(':idQuesito', $domanda['ID'], PDO::PARAM_INT);
                    $stmtOpzioni->bindParam(':titoloTest', $titoloTest, PDO::PARAM_STR);
                    $stmtOpzioni->execute();
                    $opzioni = $stmtOpzioni->fetchAll(PDO::FETCH_ASSOC);
                    $domande[$key]['opzioni'] = $opzioni;
                }
            }
            return $domande;
        } catch (PDOException $e) {
            echo "Errore nel recupero delle domande: " . $e->getMessage();
        }
        return []; // Ritorna un array vuoto in caso di errore
    }
}
