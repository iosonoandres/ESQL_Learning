<!-- cambioVisualizzazioneDesign.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambia Visualizzazione Risposte</title>
    <style>
        /* Aggiungi eventuali stili CSS se necessario */
    </style>
</head>
<body>
    <h1>Cambia Visualizzazione Risposte</h1>

    <form method="POST" action="cambioVisualizzazioneLogica.php">
        <label for="titoloTest">Titolo del Test:</label>
        <input type="text" name="titoloTest" id="titoloTest" required>

        <label for="abilitaVisualizzazione">Abilita Visualizzazione:</label>
        <select name="abilitaVisualizzazione" id="abilitaVisualizzazione" required>
            <option value="0">No</option>
            <option value="1">Sì</option>
        </select>

        <button type="submit" name="azione" value="cambiaVisualizzazione">Cambio Visualizzazione</button>
    </form>
</body>
</html>
