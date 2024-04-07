<?php
// svolgimentoTestLogica.php

require_once __DIR__ . '/root/connect.php';

ini_set('error_log', 'C:/xampp/logs/phplog.log');


class SvolgimentoTestLogica
{
    private $pdo;

    // Nel costruttore, utilizza l'oggetto PDO globale o passato come parametro
    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getTestDisponibili($emailStudente)
    {
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

    public function getDomandaSingola($titoloTest, $indiceDomanda)
    {
        $domande = $this->getDomandeTest($titoloTest);
        return $domande[$indiceDomanda] ?? null; // Restituisce la domanda all'indice specificato, se esiste.
    }

    public function salvaRispostaTemporanea($emailStudente, $titoloTest, $idQuesito, $risposta)
    {
        $_SESSION['risposte_temp'][$titoloTest][$idQuesito] = $risposta;
    }



    public function getDomandeTest($titoloTest)
    {
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



    // Funzione per inserire la risposta chiusa di uno studente
    public function inserisciRispostaChiusaStudente($emailStudente, $titoloTest, $idQuesito, $opzioneScelta)
    {
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
    public function inserisciRispostaCodiceStudente($emailStudente, $titoloTest, $idQuesito, $testoRisposta)
    {
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

    public function inviaRisposteSalvate($emailStudente, $titoloTest)
    {
        if (!empty($_SESSION['risposte_temp'][$titoloTest])) {
            foreach ($_SESSION['risposte_temp'][$titoloTest] as $idQuesito => $risposta) {
                // Determina il tipo di risposta e chiama il metodo appropriato
                // Per esempio, se la risposta è un array, potrebbe essere una risposta chiusa
                if (is_array($risposta)) {
                    // Supponendo che $risposta['opzioneScelta'] esista
                    $this->inserisciRispostaChiusaStudente($emailStudente, $titoloTest, $idQuesito, $risposta['opzioneScelta']);
                } else {
                    // Supponendo che sia una risposta aperta o di codice
                    $this->inserisciRispostaCodiceStudente($emailStudente, $titoloTest, $idQuesito, $risposta);
                }
            }
            // Pulisci le risposte temporanee per questo test
            unset($_SESSION['risposte_temp'][$titoloTest]);
        }
    }

    public function verificaSintassiRispostaCodice($queryText) {
        $syntaxValid = false;
        $errorMessage = '';
        
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("CALL dbESQL.CheckSyntax(:queryText, @syntaxValid, @errorMessageOut)");
            $stmt->bindParam(':queryText', $queryText, PDO::PARAM_STR);
            $stmt->execute();
            $stmt = null; // Chiudi lo statement corrente

            $stmt = $this->pdo->query("SELECT @syntaxValid, @errorMessageOut");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->pdo->commit(); // Commit transaction

            $syntaxValid = $result['@syntaxValid'];
            $errorMessage = $result['@errorMessageOut'];

            return ['syntaxValid' => $syntaxValid, 'errorMessage' => $errorMessage];
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw $e; // Rilancia l'eccezione per gestirla esternamente
        }
    }






    public function isTestConcludibile($emailStudente, $titoloTest)
    {
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
