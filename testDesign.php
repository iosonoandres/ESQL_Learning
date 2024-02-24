<?php 
// testDesign.php
session_start();
require_once __DIR__ . '/root/connect.php';

function getTipoUtente($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT TIPO_ACCOUNT FROM ACCOUNT WHERE EMAIL_ACCOUNT = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetchColumn();
}
$tipoUtente = isset($_SESSION['user']['email']) ? getTipoUtente($_SESSION['user']['email']) : null;



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'testLogica.php';
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset = "UTF-8">
        <meta name="viewport" content="width=devide-width, initial-scale=1.0">
        <title>Inserimento Test</title>
        <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        form {
            width: 50%;
            margin: 0 auto;
            margin-bottom: 20px;
        }

        label, input, select {
            display: block;
            margin-bottom: 10px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        hr {
            margin: 20px 0;
            border: 1px solid #ccc;
        }
    </style>
    </head>
    <body>
        <h1>Inserimento Test</h1>
        <!-- Sezione Creazione Test -->
    <form id="creaTestForm" method="POST" action="testLogica.php" enctype="multipart/form-data">
        

        <label for="titoloTest">Titolo del Test:</label>
        <input type="text" name="titoloTest" id="titoloTest" required>

        <label for="dataTest">Data del Test:</label>
        <input type="date" name="dataTest" id="dataTest" required>

        <label for="fotoTest">Foto del Test (BLOB):</label>
        <input type="file" name="fotoTest" id="fotoTest" accept="image/*" required>

       
        <button type="submit" name="azione" value="creaTest">Crea Test</button>
    </form>

    <hr>

    <!-- 
        
    QUESTA PARTE DA VALUTARE

    
     <label for="visualizzaRisposte">Visualizza Risposte:</label>
        <select name="visualizzaRisposte" id="visualizzaRisposte" required>
            <option value="1">Sì</option>
            <option value="0">No</option>
        </select>





    
    Altri elementi UI DA INSERIRE





-->
    </body>
</html>