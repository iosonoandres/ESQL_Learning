<?php
session_start();

require_once __DIR__ . '/root/connect.php'; // Assicurati che il percorso sia corretto

function getTestDisponibili()
{
    global $pdo;
    try {
        // Modificata per rimuovere il riferimento a 'id', non presente nella definizione della tabella TEST
        $stmt = $pdo->query("SELECT titolo FROM TEST");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Errore nel recupero dei test: " . $e->getMessage());
        return [];
    }
}

function getMessaggiPerTest($titoloTest)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            SELECT 
                titolo, 
                testo, 
                data, 
                emailStudenteMittente, 
                emailDocenteMittente, 
                CASE
                    WHEN emailStudenteMittente IS NOT NULL THEN 'Studente'
                    WHEN emailDocenteMittente IS NOT NULL THEN 'Docente'
                    ELSE 'Sconosciuto'
                END as tipo
            FROM MESSAGGIO 
            WHERE titoloTest = :titoloTest 
            ORDER BY data
        ");
        $stmt->execute(['titoloTest' => $titoloTest]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Errore nel recupero dei messaggi per il test $titoloTest: " . $e->getMessage());
        return [];
    }
}




// Logica per determinare quale test è stato selezionato, se applicabile
$messaggiSelezionati = [];
if (isset($_GET['titoloTest'])) {
    $titoloTestSelezionato = $_GET['titoloTest'];
    $messaggiSelezionati = getMessaggiPerTest($titoloTestSelezionato);
}

$testDisponibili = getTestDisponibili();

// Imposta i dati recuperati nelle variabili di sessione per il passaggio alla pagina di design
$_SESSION['testDisponibili'] = $testDisponibili;
$_SESSION['messaggiSelezionati'] = $messaggiSelezionati;
