<?php
// inserimentoTabellaDesign.php

session_start();
// Assicurati che il file di connessione al database sia incluso
require_once __DIR__ . '/root/connect.php';

function getTipoUtente($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT TIPO_ACCOUNT FROM ACCOUNT WHERE EMAIL_ACCOUNT = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetchColumn();
}

$tipoUtente = isset($_SESSION['user']['email']) ? getTipoUtente($_SESSION['user']['email']) : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'inserimentoTabellaLogica.php';
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creazione Tabella di Esercizio</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Open Sans', Arial, sans-serif;
            background-color: #f7f7f7;
            color: #565656;
        }
        .container {
            padding: 40px;
            max-width: 800px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, .05);
        }
        .form-control, .btn {
            border-radius: 20px;
        }
        .btn-primary {
            padding: 10px 20px;
            font-weight: bold;
        }
        input:read-only {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Creazione Tabella di Esercizio</h1>
    <!-- Sezione Creazione Tabella -->
    <form id="creaTabellaForm" method="POST" action="" class="mb-4">
        <!-- Campo nascosto per specificare l'azione -->
        <input type="hidden" name="azione" value="gestisciCreazioneTabella">
        <div class="form-group">
            <label for="nomeTabella">Nome Tabella:</label>
            <input type="text" class="form-control" name="nomeTabella" id="nomeTabella" required>
        </div>
        <div class="form-group">
            <label for="metaDati">Metadati (LO SPAZIO SI USA SOLO TRA ATTRIBUTO PRECEDENTE E SUCCESSIVO, ESEMPIO: id#INT#true# nome#text#false# anni#INT#false#):</label>
            <textarea class="form-control" name="metaDati" id="metaDati" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <label for="integritaReferenziale">Integrità Referenziale (attrib1#attrib2#tab2# Lasciare vuota nel caso non ci siano):</label>
            <textarea class="form-control" name="integritaReferenziale" id="integritaReferenziale" rows="4"></textarea>
        </div>
        <button type="button" class="btn btn-info" onclick="visualizzaInput()">Visualizza Input</button>
        <button type="submit" class="btn btn-primary" name="azione" value="creaTabella">Crea Tabella</button>
    </form>

    <hr>

    <!-- Sezione Inserimento Riga -->
    <h2>Inserimento Riga in Tabella Esercizio</h2>
    <form id="inserisciRigaForm" method="POST" action="" class="mt-4">
        <div class="form-group">
            <label for="inputRiga">Attributi della Riga (separati da #):</label>
            <input type="text" class="form-control" name="inputRiga" id="inputRiga" required placeholder="Esempio: 1# Rex# 3#">
        </div>
        <div class="form-group">
            <label for="nomeTabellaRiga">Nome Tabella:</label>
            <input type="text" class="form-control" name="nomeTabellaRiga" id="nomeTabellaRiga" required>
        </div>
        <button type="submit" class="btn btn-primary" name="azione" value="inserisciRiga">Inserisci Riga</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script>
    function visualizzaInput() {
        var nomeTabella = document.getElementById("nomeTabella").value;
        var metaDati = document.getElementById("metaDati").value;
        var integritaReferenziale = document.getElementById("integritaReferenziale").value;
        alert("Input che verrà passato alla procedura:\n\nNome Tabella: " + nomeTabella + "\nMetadati: " + metaDati + "\nIntegrità Referenziale: " + integritaReferenziale);
    }
</script>
</body>
</html>
