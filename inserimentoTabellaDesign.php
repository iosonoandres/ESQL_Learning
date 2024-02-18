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

        /* Stile per l'input email in modalità readonly */
        input:read-only {
            background-color: #f2f2f2; /* Grigio chiaro */
        }
    </style>
</head>
<body>
    <h1>Creazione Tabella di Esercizio</h1>

    <!-- Sezione Creazione Tabella -->
    <form id="creaTabellaForm" method="POST" action="">
        <!-- Campo nascosto per specificare l'azione -->
        <input type="hidden" name="azione" value="gestisciCreazioneTabella">

        <label for="nomeTabella">Nome Tabella:</label>
        <input type="text" name="nomeTabella" id="nomeTabella" required>

        <label for="metaDati">Metadati (LO SPAZIO SI USA SOLO TRA ATTRIBUTO PRECEDENTE E SUCCESSIVO id#INT#true# nome#text#false# anni#INT#false#):</label>
        <textarea name="metaDati" id="metaDati" rows="4" required></textarea>

        <label for="integritaReferenziale">Integrità Referenziale (attrib1#attrib2#tab2#tab1# Lasciare vuota nel caso non ci siano):</label>
        <textarea name="integritaReferenziale" id="integritaReferenziale" rows="4"></textarea>

        <button type="button" onclick="visualizzaInput()">Visualizza Input</button>

        <button type="submit" name="azione" value="creaTabella">Crea Tabella</button>
    </form>

    <hr>

    <!-- Sezione Inserimento Riga -->
    <h2>Inserimento Riga in Tabella Esercizio</h2>

    <form id="inserisciRigaForm" method="POST" action="">
        <label for="inputRiga">Attributi della Riga (separati da #):</label>
        <input type="text" name="inputRiga" id="inputRiga" required placeholder="Esempio: 1# Rex# 3#">

        <label for="nomeTabellaRiga">Nome Tabella:</label>
        <input type="text" name="nomeTabellaRiga" id="nomeTabellaRiga" required>

        <button type="submit" name="azione" value="inserisciRiga">Inserisci Riga</button>
    </form>

</body>

<!-- Aggiungi questa parte del codice nella sezione <head> del tuo HTML -->
<script>
    function visualizzaInput() {
        // Ottieni i valori dagli elementi del modulo
        var nomeTabella = document.getElementById("nomeTabella").value;
        var metaDati = document.getElementById("metaDati").value;
        var integritaReferenziale = document.getElementById("integritaReferenziale").value;

        // Costruisci una stringa contenente l'input
        var inputString = "Nome Tabella: " + nomeTabella + "\n" +
                          "Metadati: " + metaDati + "\n" +
                          "Integrità Referenziale: " + integritaReferenziale;

        // Visualizza l'input in una finestra modale
        alert("Input che verrà passato alla procedura:\n\n" + inputString);
    }
</script>

</html>