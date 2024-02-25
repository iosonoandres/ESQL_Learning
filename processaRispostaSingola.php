<?php
session_start();
require_once __DIR__ . '/root/connect.php';
require_once __DIR__ . '/SvolgimentoTestLogica.php';

if (!isset($_SESSION['user']['email'])) {
    exit("Utente non autorizzato.");
}

$emailStudente = $_SESSION['user']['email'];
$titoloTest = $_POST['titoloTest'];
$indiceCorrente = $_POST['indice'];
$svolgimentoTestLogica = new SvolgimentoTestLogica();
$numeroDomande = count($svolgimentoTestLogica->getDomandeTest($titoloTest));

// Ottiene l'ID del quesito e la risposta data dall'utente
$idQuesito = key($_POST['risposta']);
$risposta = $_POST['risposta'][$idQuesito];

// Processa la risposta data dall'utente
if (is_numeric($risposta)) {
    // Assumiamo che sia una risposta chiusa
    $result = $svolgimentoTestLogica->inserisciRispostaChiusaStudente($emailStudente, $titoloTest, $idQuesito, $risposta);
} else {
    // Assumiamo che sia una risposta di codice
    $result = $svolgimentoTestLogica->inserisciRispostaCodiceStudente($emailStudente, $titoloTest, $idQuesito, $risposta);
}

if (!$result) {
    echo "Si è verificato un errore nell'invio della risposta.";
} else {
    // Incrementa l'indice per passare alla domanda successiva
    $indiceCorrente++;
    if ($indiceCorrente < $numeroDomande) {
        // Reindirizza l'utente alla domanda successiva
        header("Location: svolgimentoTestDesign.php?titoloTest=" . urlencode($titoloTest) . "&indice=" . $indiceCorrente);
        exit;
    } else {
        // Tutte le domande sono state risposte, reindirizza l'utente a una pagina di fine test o visualizza un messaggio di completamento
        echo "Hai completato il test!";
        // Qui puoi reindirizzare l'utente a una pagina di riepilogo o di ringraziamento
        // header("Location: paginaDiFineTest.php");
        exit;
    }
}
?>
