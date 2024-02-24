<?php
// svolgimentoTestLogica.php

require_once __DIR__ . '/root/connect.php';

class SvolgimentoTestLogica {
    private $pdo;

    // Nel costruttore, utilizza l'oggetto PDO globale o passato come parametro
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getTestDisponibili($emailStudente) {
        $testDisponibili = [];
        try {
            $stmt = $this->pdo->prepare("SELECT t.titolo, t.data, t.foto 
                                         FROM TEST t
                                         LEFT JOIN SVOLGIMENTO s ON t.titolo = s.titoloTest AND s.emailStudente = :emailStudente
                                         WHERE s.emailStudente IS NULL OR s.stato != 'Concluso'");
            $stmt->bindParam(':emailStudente', $emailStudente, PDO::PARAM_STR);
            $stmt->execute();
            $testDisponibili = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Errore nel recupero dei test disponibili: " . $e->getMessage();
        }
        return $testDisponibili;
    }
    

    public function getDomandeTest($titoloTest) {
        $domande = [];
        try {
            // Seleziona prima tutte le domande del test
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
                    $stmtOpzioni = $this->pdo->prepare("SELECT Numerazione, testo 
                                                        FROM OPZIONE 
                                                        WHERE idQuesitoChiusa = :idQuesito AND titoloTest = :titoloTest");
                    $stmtOpzioni->bindParam(':idQuesito', $domanda['ID'], PDO::PARAM_INT);
                    $stmtOpzioni->bindParam(':titoloTest', $titoloTest, PDO::PARAM_STR);
                    $stmtOpzioni->execute();
                    $domande[$key]['opzioni'] = $stmtOpzioni->fetchAll(PDO::FETCH_ASSOC);
                }
            }
        } catch (PDOException $e) {
            echo "Errore nel recupero delle domande: " . $e->getMessage();
        }
        return $domande;
    }
    
    public function getTestImage($titoloTest) {
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



    // Funzione per inserire la risposta chiusa di uno studente
    public function inserisciRispostaChiusaStudente($emailStudente, $titoloTest, $idQuesito, $opzioneScelta) {
        try {
            // Prepara la chiamata alla procedura
            $stmt = $this->pdo->prepare("CALL dbESQL.InserisciRispostaChiusaStudente(:inputEmailStudente, :inputTitoloTest, :inputIDQuesito, :opzioneScelta)");

            // Associa i parametri
            $stmt->bindParam(':inputEmailStudente', $emailStudente, PDO::PARAM_STR);
            $stmt->bindParam(':inputTitoloTest', $titoloTest, PDO::PARAM_STR);
            $stmt->bindParam(':inputIDQuesito', $idQuesito, PDO::PARAM_INT);
            $stmt->bindParam(':opzioneScelta', $opzioneScelta, PDO::PARAM_INT);

            // Esegue la procedura
            $stmt->execute();

            // La procedura gestisce l'inserimento della risposta, l'aggiornamento dello stato di svolgimento,
            // la verifica dell'esito della risposta, e l'aggiornamento del numero di risposte al quesito.
            return true;
        } catch (PDOException $e) {
            echo "Errore nell'inserimento della risposta chiusa: " . $e->getMessage();
            return false;
        }
    }


    // Funzione per inserire la risposta di codice di uno studente e verificarne l'esito
    public function inserisciRispostaCodiceStudente($emailStudente, $titoloTest, $idQuesito, $testoRisposta) {
    try {
        // Prepara la chiamata alla procedura
        $stmt = $this->pdo->prepare("CALL dbESQL.InserisciRispostaCodiceStudente(:inputEmailStudente, :inputTitoloTest, :inputIDQuesito, :inputTestoRisposta)");

        // Associa i parametri
        $stmt->bindParam(':inputEmailStudente', $emailStudente, PDO::PARAM_STR);
        $stmt->bindParam(':inputTitoloTest', $titoloTest, PDO::PARAM_STR);
        $stmt->bindParam(':inputIDQuesito', $idQuesito, PDO::PARAM_INT);
        $stmt->bindParam(':inputTestoRisposta', $testoRisposta, PDO::PARAM_STR);

        // Esegue la procedura
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        echo "Errore nell'inserimento della risposta di codice: " . $e->getMessage();
        return false;
    }
}





    public function isTestConcludibile($emailStudente, $titoloTest) {
        try {
            $stmt = $this->pdo->prepare("SELECT stato 
                                         FROM SVOLGIMENTO 
                                         WHERE titoloTest = :titoloTest AND emailStudente = :emailStudente");
            $stmt->bindParam(':titoloTest', $titoloTest, PDO::PARAM_STR);
            $stmt->bindParam(':emailStudente', $emailStudente, PDO::PARAM_STR);
            $stmt->execute();
            $risultato = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Se non esiste uno svolgimento per questo test e studente, o lo stato è diverso da 'Concluso',
            // lo studente può svolgere o continuare il test
            if (!$risultato || $risultato['stato'] !== 'Concluso') {
                return true;
            }
        } catch (PDOException $e) {
            echo "Errore durante il controllo dello stato del test: " . $e->getMessage();
        }
        return false;
    }
    



}

// Creazione dell'oggetto SvolgimentoTestLogica
// Passa l'oggetto PDO alla classe per utilizzare la connessione esistente
$svolgimentoTestLogica = new SvolgimentoTestLogica();

?>
