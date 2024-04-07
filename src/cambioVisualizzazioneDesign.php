<!-- cambioVisualizzazioneDesign.php -->
<?php
session_start();
require_once __DIR__ . '/root/connect.php';
function getSelectTest()
{
    global $pdo;
    try {
        // Query per recuperare tutti i test disponibili
        // Modifica questa query in base ai criteri desiderati
        $stmt = $pdo->prepare("SELECT titolo FROM TEST");
        $stmt->execute();
        $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $selectHtml = '<select class="form-control" name="titoloTest" id="titoloTest" required>';
        foreach ($tests as $test) {
            $selectHtml .= '<option value="' . htmlspecialchars($test['titolo']) . '">' . htmlspecialchars($test['titolo']) . '</option>';
        }
        $selectHtml .= '</select>';
        return $selectHtml;
    } catch (PDOException $e) {
        error_log('Errore durante il recupero dei test: ' . $e->getMessage());
        return '<select class="form-control" name="titoloTest" id="titoloTest" required><option value="">Errore nel caricamento dei test</option></select>';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambia Visualizzazione Risposte</title>
    <style>
        body {
            font-family: 'Open Sans', Arial, sans-serif;
            background-color: #f7f7f7;
            color: #565656;
            display: flex;
            height: 100vh;
            margin: 0;
        }

        .container {
            padding: 40px;
            max-width: 700px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, .1);
            margin: auto; /* Center the container horizontally */
        }

        h1 {
            margin-bottom: 20px;
            /* Add margin-bottom to create space below h1 */
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control,
        .btn-primary {
            border-radius: 20px;
        }

        .btn-primary {
            padding: 10px 20px;
            font-weight: bold;
            background-color: #007bff;
            color: #fff;
            border: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Cambia Visualizzazione Risposte</h1>

        <form method="POST" action="cambioVisualizzazioneLogica.php">
            <div class="form-group">
                <label for="titoloTestCodice">Test associato:</label>
                <?php echo getSelectTest(); ?>
            </div>

            <label for="abilitaVisualizzazione">Abilita Visualizzazione:</label>
            <select name="abilitaVisualizzazione" id="abilitaVisualizzazione" required>
                <option value="0">No</option>
                <option value="1">Sì</option>
            </select>

            <button type="submit" name="azione" value="cambiaVisualizzazione">Cambio Visualizzazione</button>
        </form>
    </div>
</body>

</html>