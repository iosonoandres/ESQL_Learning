<?php
// Include la tua classe svolgimentoTestLogica.php
require_once 'svolgimentoTestLogica.php';

// Assumi che il titolo del test sia noto
$titoloTest = "nome_del_tuo_test";

// Inizializza la variabile $ID
$ID = 1;

// Itera finché ci sono quesiti disponibili
while ($quesito = fetch_question($titoloTest, $ID)) {
    // Visualizza il titolo del quesito
    echo "<h3>Quesito $ID: {$quesito['titoloTest']}</h3>";

    // Visualizza il testo del quesito
    echo "<p>{$quesito['descrizione']}</p>";

    // Aggiungi uno spazio di input per la risposta
    echo "<textarea name='risposta_$ID' rows='4' cols='50'></textarea><br>";

    // Aggiungi un pulsante "Salva risposta" associato al quesito corrente, aggiungere onclick='save_answer($titoloTest, $idQuesito, $risposta)'
    echo "<button>Salva risposta</button><br><br>";

    // Incrementa il numero del quesito per la prossima iterazione
    $ID++;
}
?>

<!-- Pulsante per inviare il tentativo, aggiungere  onclick="inviaTentativo()" -->
<button>Invia tentativo</button>


