<!DOCTYPE html>
<html lang="en">
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
    </style>
</head>
<body>
    <h1>Creazione Tabella di Esercizio</h1>

    <!-- Sezione Creazione Tabella -->
    <form id="creaTabellaForm" method="POST" action="gestioneInserimentoTabella.php">
        <label for="nomeTabella">Nome Tabella:</label>
        <input type="text" name="nomeTabella" id="nomeTabella" required>

        <label for="emailDocente">Email Docente:</label>
        <input type="email" name="emailDocente" id="emailDocente" required>

        <label for="metaDati">Metadati (attributo1#tipo1#primaria# ...):</label>
        <textarea name="metaDati" id="metaDati" rows="4" required></textarea>

        <label for="integritaReferenziale">Integrità Referenziale (attrib1#attrib2#tab2# ...):</label>
        <textarea name="integritaReferenziale" id="integritaReferenziale" rows="4"></textarea>

        <button type="submit" name="azione" value="creaTabella">Crea Tabella</button>
    </form>

    <hr>

    <!-- Sezione Inserimento Riga -->
    <h2>Inserimento Riga in Tabella Esercizio</h2>

    <form id="inserisciRigaForm" method="POST" action="gestioneInserimentoTabella.php">
        <label for="inputRiga">Attributi della Riga (separati da #):</label>
        <input type="text" name="inputRiga" id="inputRiga" required placeholder="Esempio: 1#Cavallo#Girolamo">

        <label for="nomeTabellaRiga">Nome Tabella:</label>
        <input type="text" name="nomeTabellaRiga" id="nomeTabellaRiga" required>

        <label for="emailDocenteRiga">Email Docente:</label>
        <input type="email" name="emailDocenteRiga" id="emailDocenteRiga" required>

        <label for="numAttributiAttesi">Numero di Attributi Attesi:</label>
        <input type="number" name="numAttributiAttesi" id="numAttributiAttesi" required>

        <button type="submit" name="azione" value="inserisciRiga">Inserisci Riga</button>
    </form>

    <script>
        // Aggiungi le funzioni JavaScript necessarie per gestire l'inserimento di una riga
        // ...
    </script>
</body>
</html>
