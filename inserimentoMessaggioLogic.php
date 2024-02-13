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
if ($tipoUtente === 'studente' && isset($_POST['inserisciCommento'])) {
    $titolo = $_POST['titolo']; // Il titolo del messaggio inviato dal form.
    $testo = $_POST['testo']; // Il testo del messaggio inviato dal form.
    $titoloTest = $_POST['titoloTest']; // Il titolo del test selezionato nel form.
    $emailStudente = $_SESSION['user']['email']; // Email dello studente dalla sessione.
    // Devi aggiungere un campo nel form per l'email del docente destinatario.
    $emailDocenteDestinatario = $_POST['emailDocente']; // Assumi che questo campo sia stato aggiunto al form.

    // Prepara la chiamata alla stored procedure
    $stmt = $pdo->prepare("CALL dbESQL.InserisciMessaggioStudente(:inputTitolo, :inputTesto, :inputTitoloTest, :inputEmailStudenteMittente, :inputEmailDocenteDestinatario)");

    // Esegui la stored procedure con i parametri forniti
    $stmt->execute([
        'inputTitolo' => $titolo,
        'inputTesto' => $testo,
        'inputTitoloTest' => $titoloTest,
        'inputEmailStudenteMittente' => $emailStudente,
        'inputEmailDocenteDestinatario' => $emailDocenteDestinatario,
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



