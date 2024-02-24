<?php
// inserimetoTabellaLogica.php
require_once __DIR__ . '/root/connect.php';

//includi la classe LoggerMogo
require_once __DIR__ . '../root/LoggerMongo.php';


function getEmailDocente()
{
    // Assuming the session contains the user's email
    return isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : '';
}

function inserisciTabellaEsercizio($inputNomeTabella, $inputEmailDocente, $metaDati, $integritaReferenziale)
{
    global $pdo;
    // inizializza il logger
    $logger = new LoggerMongo("mongodb://localhost:27017", "logDB");


    $emailDocente = getEmailDocente();

    try {
        // Prepare the SQL statement to call the stored procedure
        $stmt = $pdo->prepare("CALL InserisciTabellaDiEsercizio(?, ?, ?, ?)");

        // Bind parameters
        $stmt->bindParam(1, $inputNomeTabella, PDO::PARAM_STR);
        $stmt->bindParam(2, $emailDocente, PDO::PARAM_STR);
        $stmt->bindParam(3, $metaDati, PDO::PARAM_STR);
        $stmt->bindParam(4, $integritaReferenziale, PDO::PARAM_STR);

        // Execute the stored procedure
        $stmt->execute();

        // Fetch the result (assuming it returns a single row with a 'Messaggio' field)
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Close the statement
        $stmt->closeCursor();

        // Return the result
        $logger->logEvent('TableCreation', "Creata tabella $inputNomeTabella da $emailDocente");
        return 'Tabella creata con successo!';
        return $result['Messaggio'];
    } catch (PDOException $e) {
        // Handle exceptions here
        $logger->logEvent('FailedTableCreation', "Tentativo fallito creazione tabella $inputNomeTabella da $emailDocente");
        return "Error: " . $e->getMessage();
    }
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emailDocente = getEmailDocente();
    $azione = $_POST['azione'] ?? '';

    if ($azione === 'creaTabella') {
        // Assuming the form fields have specific names, adjust them accordingly
        $inputNomeTabella = $_POST['nomeTabella'] ?? '';
        $metaDati = $_POST['metaDati'] ?? '';
        $integritaReferenziale = $_POST['integritaReferenziale'] ?? '';

        // Call the function to insert the table
        $message = inserisciTabellaEsercizio($inputNomeTabella, $emailDocente, $metaDati, $integritaReferenziale);

        // Output the result or handle it as needed
        echo $message;
    }
}

function inserisciRigaTabellaEsercizio($inputRiga, $nomeTabella)
{
    global $pdo;
    // inizializza il logger
    $logger = new LoggerMongo("mongodb://localhost:27017", "logDB");

    $emailDocente = getEmailDocente();


    try {
        // Prepare the SQL statement to call the stored procedure
        $stmt = $pdo->prepare("CALL InserisciRigaTabellaEsercizio(?, ?)");

        // Bind parameters
        $stmt->bindParam(1, $inputRiga, PDO::PARAM_STR);
        $stmt->bindParam(2, $nomeTabella, PDO::PARAM_STR);

        // Execute the stored procedure
        $stmt->execute();

        // Close the statement
        $stmt->closeCursor();

        // Assuming the stored procedure doesn't return any specific result, handle success as needed
        $logger->logEvent('LineInsertion', "Inserita Riga in $nomeTabella da $emailDocente");
        return 'Riga inserita con successo!';
    } catch (PDOException $e) {
        // Handle exceptions here
        $logger->logEvent('FailedLineInsertion', "Tentativo fallito inserimento Riga in $nomeTabella da $emailDocente");
        return "Error: " . $e->getMessage();
    }
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $azione = $_POST['azione'] ?? '';

    if ($azione === 'inserisciRiga') {
        // Assuming the form fields have specific names, adjust them accordingly
        $inputRiga = $_POST['inputRiga'] ?? '';
        $nomeTabellaRiga = $_POST['nomeTabellaRiga'] ?? '';

        // Call the function to insert the row
        $message = inserisciRigaTabellaEsercizio($inputRiga, $nomeTabellaRiga);

        // Output the result or handle it as needed
        echo $message;
    }
}
