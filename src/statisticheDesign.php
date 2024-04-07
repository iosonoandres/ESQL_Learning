<?php
// statistheDesign.php
// ... (Codice HTML e inclusione necessaria per connessione o sessione)

// Includi il file di logica per ottenere le variabili
include 'statisticheLogica.php';
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h3 {
            color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tbody tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Rimane invariato il codice precedente per l'header e la gestione degli alert -->

        <!-- Aggiunta del blocco per visualizzare la classifica degli studenti per test completati -->
        <h3>Classifica degli studenti per test completati:</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Codice Studente</th>
                    <th>Numero Test Completati</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classificaTest as $row) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['CodiceStudente']); ?></td>
                        <td><?php echo htmlspecialchars($row['NumeroTestCompletati']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Aggiunta del blocco per visualizzare la classifica degli studenti per risposte corrette -->
        <h3>Classifica degli studenti per risposte corrette:</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Codice Studente</th>
                    <th>Percentuale Risposte Corrette</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classificaRisposte as $row) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['CodiceStudente']); ?></td>
                        <td><?php echo htmlspecialchars($row['PercentualeRisposteCorrette']); ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Aggiunta del blocco per visualizzare la classifica dei quesiti -->
        <h3>Classifica dei quesiti:</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID Quesito</th>
                    <th>Titolo Test</th>
                    <th>Numero Risposte</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classificaQuesiti as $row) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['IDQuesito']); ?></td>
                        <td><?php echo htmlspecialchars($row['TitoloTest']); ?></td>
                        <td><?php echo htmlspecialchars($row['NumeroRisposte']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Rimane invariato il codice precedente per gli script JavaScript e librerie esterne -->
</body>

</html>