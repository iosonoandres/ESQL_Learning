<?php
// guardaTestLogica.php
require_once __DIR__ . '/root/connect.php';

class GuardaTestLogica
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

    public function getSketchTextForQuestion($idQuesitoCodice, $titoloTest)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT Sketch FROM SOLUZIONE WHERE IdQuesitoCodice = :idQuesitoCodice AND titoloTest = :titoloTest");
            $stmt->bindParam(':idQuesitoCodice', $idQuesitoCodice, PDO::PARAM_INT);
            $stmt->bindParam(':titoloTest', $titoloTest, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($result) && isset($result['Sketch'])) {
                return $result['Sketch'];
            }
        } catch (PDOException $e) {
            echo "Error retrieving sketch text: " . $e->getMessage();
        }

        return null;
    }
}
