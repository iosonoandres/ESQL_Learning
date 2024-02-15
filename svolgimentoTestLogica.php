<?php
session_start();
require_once __DIR__ . '/root/connect.php';

function getEmailUtente($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT EMAIL_ACCOUNT FROM ACCOUNT WHERE EMAIL_ACCOUNT = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetchColumn();
}

$emailStudente = isset($_SESSION['user']['email']) ? getEmailUtente($_SESSION['user']['email']) : null;


// logica per prendere i quesiti del test dal database per mostrarli su svolgimentoTestDesign.php
function fetch_question($titoloTest, $ID) {
    global $pdo;
    try {
        $query = "SELECT QUESITO.*
                  FROM RIFERIMENTO
                  INNER JOIN QUESITO ON RIFERIMENTO.IDquesito = QUESITO.ID
                  WHERE RIFERIMENTO.titoloTest = :titoloTest
                  AND RIFERIMENTO.ID = :ID";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':titoloTest', $titoloTest, PDO::PARAM_STR);
        $stmt->bindParam(':ID', $ID, PDO::PARAM_INT);
        $stmt->execute();

        $quesito = $stmt->fetch(PDO::FETCH_ASSOC);

        return $quesito;

    } catch (PDOException $e) {
        echo "Errore nel recupero del quesito: " . $e->getMessage();
        return null;
    }
}

// logica per salvare risposte ogni volta che studente clicca sul bottone salva risposta sotto al quesito. 
function save_answer($titoloTest, $idQuesito, $risposta) {
    global $pdo;
    try {
        $data = date('Y-m-d H:i:s');

        // Inserisci una nuova risposta generica nella tabella RISPOSTA
        $query = "INSERT INTO RISPOSTA (data, emailStudente) VALUES (:data, :emailStudente)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':data', $data, PDO::PARAM_STR);
        $stmt->bindParam(':emailStudente', $emailStudente, PDO::PARAM_STR);
        $stmt->execute();

        // Ottieni l'ID della risposta appena inserita
        $rispostaID = $pdo->lastInsertId();

        // Inserisci la risposta nella tabella corrispondente
        $query = "INSERT INTO RISPOSTA_GENERICA (data, IDQuesito, titoloTest, emailStudente, risposta) 
                  VALUES (:data, :idQuesito, :titoloTest, :emailStudente, :risposta)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':data', $data, PDO::PARAM_STR);
        $stmt->bindParam(':idQuesito', $idQuesito, PDO::PARAM_INT);
        $stmt->bindParam(':titoloTest', $titoloTest, PDO::PARAM_STR);
        $stmt->bindParam(':emailStudente', $emailStudente, PDO::PARAM_STR);
        $stmt->bindParam(':risposta', $risposta, PDO::PARAM_STR);
        $stmt->execute();

        return true;

    } catch (PDOException $e) {
        echo "Errore nel salvataggio della risposta: " . $e->getMessage();
        return false;
    }
}
?>
