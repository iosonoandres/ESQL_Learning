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
                                         WHERE s.emailStudente IS NULL OR s.stato = 'Concluso' OR s.stato = 'InCompletamento' ");
            $stmt->bindParam(':emailStudente', $emailStudente, PDO::PARAM_STR);
            $stmt->execute();
            $testDisponibili = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Errore nel recupero dei test disponibili: " . $e->getMessage();
        }
        return $testDisponibili;
    }

    public function getRisposteStudente($emailStudente, $titoloTest)
    {
        $risposte = [];
        try {
            // Ottenere l'elenco dei quesiti per il test specificato
            $quesitiStmt = $this->pdo->prepare("SELECT ID, descrizione FROM QUESITO WHERE titoloTest = :titoloTest");
            $quesitiStmt->bindParam(':titoloTest', $titoloTest, PDO::PARAM_STR);
            $quesitiStmt->execute();
            $quesiti = $quesitiStmt->fetchAll(PDO::FETCH_ASSOC);

            // Per ogni quesito, ottenere l'esito tramite la stored procedure e includere la descrizione
            foreach ($quesiti as $quesito) {
                $esito = $this->getEsitoRisposta($emailStudente, $titoloTest, $quesito['ID']);
                // Aggiungere l'esito, la descrizione e le altre informazioni necessarie all'array $risposte
                $risposte[] = [
                    'ID' => $quesito['ID'],
                    'descrizione' => $quesito['descrizione'], // Aggiunta della descrizione del quesito
                    'esito' => $esito
                    // Aggiungi altre informazioni se necessario
                ];
            }
        } catch (PDOException $e) {
            echo "Errore nel recupero delle risposte dello studente: " . $e->getMessage();
        }
        return $risposte;
    }



    // Metodo per recuperare il nome e il cognome dello studente
    public function getNomeCognomeStudente($emailStudente)
    {
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
    public function getTestSvolti($emailStudente)
    {
        $testSvolti = [];
        try {
            $stmt = $this->pdo->prepare("SELECT t.titolo, t.data, s.stato 
                                         FROM TEST t
                                         JOIN SVOLGIMENTO s ON t.titolo = s.titoloTest
                                         WHERE s.emailStudente = :emailStudente AND s.stato != 'Aperto'");
            $stmt->bindParam(':emailStudente', $emailStudente, PDO::PARAM_STR);
            $stmt->execute();
            $testSvolti = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Errore nel recupero dei test svolti: " . $e->getMessage();
        }
        return $testSvolti;
    }

    public function getEsitoRisposta($idQuesito, $titoloTest, $emailStudente)
    {
        $esitoOut = null;
        $rispostaCorretta = null; // Questa sarà utilizzata solo se il docente ha abilitato la visualizzazione
        
        try {
            // Chiamata alla stored procedure per ottenere l'esito e la risposta corretta
            $stmt = $this->pdo->prepare("CALL dbESQL.EstraiOpzioneOSketch(?,?,?, @rispostaCorretta, @esitoOut)");
            $stmt->bindParam(1, $idQuesito, PDO::PARAM_INT);
            $stmt->bindParam(2, $titoloTest, PDO::PARAM_STR);
            $stmt->bindParam(3, $emailStudente, PDO::PARAM_STR);
    
            $stmt->execute();
            
            // Recupera l'esito dalla variabile di output della stored procedure
            $esitoStmt = $this->pdo->query("SELECT @esitoOut AS esitoOut");
            $esitoResult = $esitoStmt->fetch(PDO::FETCH_ASSOC);
            $esitoOut = $esitoResult['esitoOut'];
    
            // Verifica se il docente ha abilitato la visualizzazione delle risposte
            $stmtVisualizzaRisposte = $this->pdo->prepare("SELECT visualizzaRisposte FROM TEST WHERE titolo = :titoloTest");
            $stmtVisualizzaRisposte->bindParam(':titoloTest', $titoloTest, PDO::PARAM_STR);
            $stmtVisualizzaRisposte->execute();
            $visualizzaRisposte = $stmtVisualizzaRisposte->fetchColumn();
            
            if ($visualizzaRisposte == 1) {
                // Se il docente ha abilitato la visualizzazione, recupera la risposta corretta
                $rispostaCorrettaStmt = $this->pdo->query("SELECT @rispostaCorretta AS rispostaCorretta");
                $rispostaCorrettaResult = $rispostaCorrettaStmt->fetch(PDO::FETCH_ASSOC);
                if ($rispostaCorrettaResult) {
                    $rispostaCorretta = $rispostaCorrettaResult['rispostaCorretta'];
                }
            }
        } catch (PDOException $e) {
            echo "Errore nell'esecuzione della stored procedure: " . $e->getMessage();
        }
    
        // Restituisce l'esito e la risposta corretta (quest'ultima solo se abilitata la visualizzazione)
        return ['esito' => $esitoOut, 'rispostaCorretta' => $rispostaCorretta];
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
