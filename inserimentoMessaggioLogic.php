<?php
session_start();

// Assicurati che il file di connessione al database sia incluso
require_once __DIR__ . '/root/connect.php';

function getTipoUtente($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT TIPO_ACCOUNT FROM ACCOUNT WHERE EMAIL_ACCOUNT = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetchColumn();
}

function getSelectTest($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT titolo FROM TEST WHERE emailDocente = :email");
    $stmt->execute(['email' => $email]);
    $select = '<select class="form-control" name="titoloTest">';
    while ($row = $stmt->fetch()) {
        $select .= '<option value="' . $row['titolo'] . '">' . $row['titolo'] . '</option>';
    }
    $select .= '</select>';
    return $select;
}

if (!isset($_SESSION['user']['email'])) {
    header('Location: login.php');
    exit();
}

$tipoUtente = getTipoUtente($_SESSION['user']['email']);

// Logica per l'inserimento del messaggio da parte del docente
if ($tipoUtente === 'DOCENTE' && isset($_POST['inserisciQuesito'])) {
    $titolo = $_POST['titolo'];
    $testo = $_POST['testo'];
    $titoloTest = $_POST['titoloTest'];
    $emailDocenteMittente = $_SESSION['user']['email'];

    // Assicurati che la stored procedure esista e sia corretta
    $stmt = $pdo->prepare("CALL InserisciMessaggioDocente(:titolo, :testo, :titoloTest, :emailDocenteMittente)");
    $stmt->execute([
        'titolo' => $titolo,
        'testo' => $testo,
        'titoloTest' => $titoloTest,
        'emailDocenteMittente' => $emailDocenteMittente,
    ]);

    // Imposta un messaggio di successo da mostrare nel file di design
    $_SESSION['message'] = 'Quesito inserito correttamente!';
}

// Logica per l'inserimento del commento da parte dello studente
if ($tipoUtente === 'STUDENTE' && isset($_POST['inserisciCommento'])) {
    $testo = $_POST['testo'];
    $titoloTest = $_GET['titoloTest']; // Assicurati che 'titoloTest' venga passato correttamente
    $emailStudente = $_SESSION['user']['email'];
$opzioneScelta = isset($_POST['opzioneScelta']) ? $_POST['opzioneScelta'] : null;


// Assicurati che la stored procedure esista e sia corretta
$stmt = $pdo->prepare("CALL InserisciRispostaStudente(:emailStudente, :titoloTest, :testo, :opzioneScelta)");
$stmt->execute([
    'emailStudente' => $emailStudente,
    'titoloTest' => $titoloTest,
    'testo' => $testo,
    'opzioneScelta' => $opzioneScelta,
]);

// Imposta un messaggio di successo da mostrare nel file di design
$_SESSION['message'] = 'Commento inserito correttamente!';

}

// Funzione per recuperare i messaggi di un test specifico
function getMessaggiTest($titoloTest) {
global $pdo;
$stmt = $pdo->prepare("SELECT testo FROM MESSAGGI WHERE titoloTest = :titoloTest");
$stmt->execute(['titoloTest' => $titoloTest]);
return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



