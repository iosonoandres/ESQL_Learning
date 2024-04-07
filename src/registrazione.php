<?php
global $pdo;
session_start();
include "root/connect.php";

//includi la classe LoggerMogo
require_once __DIR__ . '../root/LoggerMongo.php';
// inizializza il logger
$logger = new LoggerMongo("mongodb://localhost:27017", "logDB");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];
    $tipologiaUtente = $_POST["tipologiaUtente"];

    if ($tipologiaUtente === "Docente") {
        $nomeDocente = $_POST["nomeDocente"];
        $cognomeDocente = $_POST["cognomeDocente"];
        $telefonoDocente = $_POST["telefonoDocente"];
        $inputCorso = $_POST["inputCorso"];
        $inputDipartimento = $_POST["inputDipartimento"];


        $sql = "CALL ISCRIZIONE_DOCENTE(?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        // Bind parameters using PDO-style binding
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->bindParam(2, $password, PDO::PARAM_STR);
        $stmt->bindParam(3, $nomeDocente, PDO::PARAM_STR);
        $stmt->bindParam(4, $cognomeDocente, PDO::PARAM_STR);
        $stmt->bindParam(5, $telefonoDocente, PDO::PARAM_STR);
        $stmt->bindParam(6, $inputCorso, PDO::PARAM_STR);
        $stmt->bindParam(7, $inputDipartimento, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo '<script>
            alert("Registrazione Completata!");
            setTimeout(function() {
                window.location.href = "index.php";
            }, 3000); 
          </script>';
          $logger->logEvent('SuccessfulRegistration', "Registrazione eseguita con successo per l'utente $email");
            exit();
        } else {
            $logger->logEvent('FailedRegistrationAttempt', "Tentativo di registrazione fallito per l'utente $email");

            // Mantieni anche il vecchio sistema di logging
            error_log("Error: " . $stmt->errorInfo()[2]);
        }

        $stmt->closeCursor();
    }

    if ($tipologiaUtente === "Studente") {


        $nomeStudente = $_POST["nomeStudente"];
        $cognomeStudente = $_POST["cognomeStudente"];
        $telefonoStudente = $_POST["telefonoStudente"];
        $annoImmatricolazione = date('Y', strtotime($_POST["annoImmatricolazione"]));
        $inputCodice = $_POST["inputCodice"];


        $sql2 = "CALL ISCRIZIONE_STUDENTE(?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql2);

        // Bind parameters using PDO-style binding
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->bindParam(2, $password, PDO::PARAM_STR);
        $stmt->bindParam(3, $nome, PDO::PARAM_STR);
        $stmt->bindParam(4, $cognome, PDO::PARAM_STR);
        $stmt->bindParam(5, $telefono, PDO::PARAM_STR);
        $stmt->bindParam(6, $annoImmatricolazione, PDO::PARAM_STR);
        $stmt->bindParam(7, $inputCodice, PDO::PARAM_STR);


        if ($stmt->execute()) {

            /*
            try {
                $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
                $bulk = new MongoDB\Driver\BulkWrite();
                $doc = [
                    'data' => date("Y-m-d h:i:sa"),
                    'UTENTE' => $email, // Use the saved email here
                    'EVENTO' => 'Si è registrato', // Assuming this is a logout event
                ];
                $bulk->insert($doc);
                $mng->executeBulkWrite('Log.Users_log', $bulk);
            } catch (MongoDB\Driver\Exception\Exception $e) {
                echo ("Codice errore" . $e->getMessage() . "<br>");
            }

            */

            echo '<script>
            alert("Registrazione Completata!");
            setTimeout(function() {
                window.location.href = "index.php";
            }, 3000); // 3 seconds delay
          </script>';
          $logger->logEvent('SuccessfulRegistration', "Registrazione eseguita con successo per l'utente $email");
            exit(); // Make sure to exit after the JavaScript code
        } else {
            $logger->logEvent('FailedRegistrationAttempt', "Tentativo di registrazione fallito per l'utente $email");
            echo "Error: " . $stmt->errorInfo()[2]; // Use errorInfo to get the error message
        }

        $stmt->closeCursor(); // Close the cursor to release resources
    }
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <title>REGISTRAZIONE</title>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round|Open+Sans">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>

<body>

    <div class="container">
        <h1><b>REGISTRAZIONE UTENTE</b></h1>
        <form action="registrazione.php" method="POST" id="registrationForm">

            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control" required>

            <label for="password">Password:</label>
            <input type="password" name="password" class="form-control" required>

            <label for="tipologiaUtente">Tipologia:</label>
            <select name="tipologiaUtente" required id="userTypeSelect" class="form-control">
                <option value="Studente">Studente</option>
                <option value="Docente">Docente</option>
            </select>

            <div id="studenteFields" class="form-group" style="display: none;">
                <label for="nomeStudente">Nome:</label>
                <input type="text" name="nomeStudente" class="form-control" required>

                <label for="cognomeStudente">Cognome:</label>
                <input type="text" name="cognomeStudente" class="form-control" required>

                <label for="telefonoStudente">Numero di telefono:</label>
                <input type="text" name="telefonoStudente" class="form-control" required>

                <label for="annoImmatricolazione">Anno di Immatricolazione:</label>
                <input type="date" name="annoImmatricolazione" class="form-control" required>

                <label for="inputCodice">Codice Studente:</label>
                <input type="text" name="inputCodice" class="form-control" required>


            </div>

            <div id="docenteFields" class="form-group" style="display: none;">
                <label for="nomeDocente">Nome Docente:</label>
                <input type="text" name="nomeDocente" class="form-control" required>

                <label for="cognomeDocente">Cognome Docente:</label>
                <input type="text" name="cognomeDocente" class="form-control" required>

                <label for="telefonoDocente">Telefono Docente:</label>
                <input type="text" name="telefonoDocente" class="form-control" required>

                <label for="inputCorso">Input Corso:</label>
                <input type="text" name="inputCorso" class="form-control" required>

                <label for="inputDipartimento">Input Dipartimento:</label>
                <input type="text" name="inputDipartimento" class="form-control" required>

            </div>

            <input type="submit" class="btn btn-primary" value="Register">
        </form>
    </div>
</body>

</html>




<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userTypeSelect = document.getElementById('userTypeSelect');
        const studenteFields = document.getElementById('studenteFields');
        const docenteFields = document.getElementById('docenteFields');

        // Funzione per aggiornare la visibilità dei campi e gestire l'attributo 'required'
        function updateFieldsVisibility() {
            if (userTypeSelect.value === 'Studente') {
                studenteFields.style.display = 'block';
                docenteFields.style.display = 'none';
                removeRequired(docenteFields);
                addRequired(studenteFields);
            } else if (userTypeSelect.value === 'Docente') {
                studenteFields.style.display = 'none';
                docenteFields.style.display = 'block';
                removeRequired(studenteFields);
                addRequired(docenteFields);
            }
        }

        // Rimuovi l'attributo 'required' dai campi non visibili
        function removeRequired(container) {
            const fields = container.querySelectorAll('[required]');
            fields.forEach(field => {
                field.removeAttribute('required');
            });
        }

        // Aggiungi l'attributo 'required' ai campi visibili
        function addRequired(container) {
            const fields = container.querySelectorAll('input, select');
            fields.forEach(field => {
                field.setAttribute('required', '');
            });
        }

        // Aggiorna la visibilità dei campi al cambiamento della selezione
        userTypeSelect.addEventListener('change', updateFieldsVisibility);

        // Aggiorna la visibilità dei campi al caricamento della pagina per gestire il caso in cui un tipo di utente sia preselezionato
        updateFieldsVisibility();
    });
</script>


<style>
    body {
        background-image: url('design/462749.jpg');
    }

    .container {
        max-width: 600px;
        margin: 50px auto;
        padding: 40px;
        background-color: #fff;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
        text-align: center;
    }

    h1 {
        text-align: center;
        font-size: 24px;
        color: #222;
        margin-bottom: 20px;
    }

    form {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-bottom: 16px;
        font-weight: bold;
        color: #555;
        font-size: 18px;
    }

    /* Rende gli input, i select, e i button con angoli più arrotondati */
    input[type="email"],
    input[type="password"],
    select,
    input[type="text"],
    input[type="date"],
    input[type="submit"] {
        border-radius: 20px;
        /* Aumenta per angoli più arrotondati */
    }

    /* Personalizza ulteriormente i button */
    input[type="submit"] {
        background-color: #007bff;
        /* Colore primario di Bootstrap */
        border: none;
        padding: 10px 20px;
        /* Aumenta il padding per un aspetto più grande */
        font-size: 18px;
        transition: background-color 0.2s ease-in-out;
    }

    input[type="submit"]:hover,
    input[type="submit"]:focus {
        background-color: #0056b3;
        /* Colore più scuro al passaggio del mouse e al focus */
        color: #ffffff;
    }

    /* Stile per il container e il form per adattarsi meglio al design moderno */
    .container {
        border-radius: 25px;
        /* Angoli arrotondati per il container */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        /* Ombra leggera per profondità */
    }

    /* Adatta il form al design mobile-first */
    @media (max-width: 768px) {
        .container {
            padding: 20px;
            margin: 20px auto;
            /* Maggior spazio sui lati su schermi piccoli */
        }
    }
</style>