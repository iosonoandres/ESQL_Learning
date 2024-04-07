<?php
// statistheLogica.php
require_once __DIR__ . '/root/connect.php';

// Ottieni la classifica degli studenti per test completati
$queryClassificaTest = "SELECT CodiceStudente, NumeroTestCompletati FROM dbESQL.ClassificaStudentiTestCompletati";
$resultClassificaTest = $pdo->query($queryClassificaTest);
$classificaTest = array();

while ($row = $resultClassificaTest->fetch(PDO::FETCH_ASSOC)) {
    $classificaTest[] = $row;
}

// Ottieni la classifica degli studenti per risposte corrette
$queryClassificaRisposte = "SELECT CodiceStudente, PercentualeRisposteCorrette FROM dbESQL.ClassificaStudentiRisposteCorrette";
$resultClassificaRisposte = $pdo->query($queryClassificaRisposte);
$classificaRisposte = array();

while ($row = $resultClassificaRisposte->fetch(PDO::FETCH_ASSOC)) {
    $classificaRisposte[] = $row;
}

// Ottieni la classifica dei quesiti
$queryClassificaQuesiti = "SELECT IDQuesito, TitoloTest, NumeroRisposte FROM dbESQL.ClassificaQuesiti";
$resultClassificaQuesiti = $pdo->query($queryClassificaQuesiti);
$classificaQuesiti = array();

while ($row = $resultClassificaQuesiti->fetch(PDO::FETCH_ASSOC)) {
    $classificaQuesiti[] = $row;
}
?>