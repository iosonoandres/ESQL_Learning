<?php 
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
    require 'inserimentoMessaggioLogic.php';
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Inserimento Messaggio</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Open Sans', Arial, sans-serif;
            background-color: #f7f7f7;
            color: #565656;
        }
        .container {
            padding: 40px;
            max-width: 700px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,.05);
        }
        .form-control, .btn-primary {
            border-radius: 20px;
        }
        .btn-primary {
            padding: 10px 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center">Inserimento Messaggio</h1>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['message']; ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <?php if ($tipoUtente === 'docente'): ?>
        <form method="post" action="inserimentoMessaggioLogic.php">
            <div class="form-group">
                <label for="titolo">Titolo:</label>
                <input type="text" class="form-control" name="titolo" id="titolo" required>
            </div>

            <div class="form-group">
                <label for="testo">Testo:</label>
                <textarea class="form-control" name="testo" id="testo" required></textarea>
            </div>

            <div class="form-group">
                <label for="titoloTest">Test associato:</label>
                <?php echo getSelectTest($_SESSION['user']['email']); ?>
            </div>

            <button type="submit" name="inserisciQuesito" class="btn btn-primary">Inserisci quesito</button>
        </form>
    <?php elseif ($tipoUtente === 'studente'): ?>
        <form method="post" action="inserimentoMessaggioLogic.php">
            <div class="form-group">
                <label for="testo">Commento:</label>
                <textarea class="form-control" name="testo" id="testo" required></textarea>
            </div>

            <!-- Assumendo che ci sia un campo per selezionare il test associato -->
            <div class="form-group">
                <label for="titoloTest">Test associato:</label>
                <select class="form-control" name="titoloTest">
                    <!-- Opzioni del test da generare dinamicamente -->
                </select>
            </div>

            <div class="form-group">
                <label for="emailDocente">Email Docente Destinatario:</label>
                <input type="email" class="form-control" name="emailDocente" id="emailDocente" required>
            </div>

            <!-- Assumendo che il commento dello studente possa avere opzioni -->
            <div class="form-group">
                <label>Opzioni:</label>
                <div>
                    <!-- Genera le opzioni dinamicamente in base al quesito -->
                    <!-- Esempio statico: -->
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="opzioneScelta" id="opzione1" value="1">
                        <label class="form-check-label" for="Dubbio">
                            Dubbio
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="opzioneScelta" id="opzione2" value="2">
                        <label class="form-check-label" for="Possibile errore">
                            Possibile errore
                        </label>
                    </div>
                    <!-- Aggiungi ulteriori opzioni se necessario -->
                </div>
            </div>

            <button type="submit" name="inserisciCommento" class="btn btn-primary">Inserisci commento</button>
        </form>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>