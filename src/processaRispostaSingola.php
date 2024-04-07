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
    $result = $svolgimentoTestLogica->inserisciRispostaChiusaStudente($emailStudente, $titoloTest, $idQuesito, $risposta);
} else {
    try {
        $verifica = $svolgimentoTestLogica->verificaSintassiRispostaCodice($risposta);
    
        if (!$verifica['syntaxValid']) {
            // Usa JavaScript per mostrare un alert e poi reindirizza l'utente
            $errorMessage = addslashes($verifica['errorMessage']); // Previene problemi con apici nel messaggio
            echo "<script>alert('Errore di sintassi nella risposta: $errorMessage'); window.history.back();</script>";
            exit;
        } else {
            $result = $svolgimentoTestLogica->inserisciRispostaCodiceStudente($emailStudente, $titoloTest, $idQuesito, $risposta);
        }
    } catch (PDOException $e) {
        $errorMessage = addslashes($e->getMessage()); // Previene problemi con apici nel messaggio
        echo "<script>alert('Si è verificato un errore nella verifica della sintassi: $errorMessage'); window.history.back();</script>";
        exit;
    }
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
        echo '<script>alert("Hai completato il test! Verrai reinderizzato alla dashboard"); window.location.href = "dashboard.php";</script>';

        // Qui puoi reindirizzare l'utente a una pagina di riepilogo o di ringraziamento
        // header("Location: paginaDiFineTest.php");
        exit;
    }
}
?>
