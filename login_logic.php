<?php
session_start();
require_once __DIR__ . '/root/connect.php'; // Collegamento con il connect

function attempt_login($pdo, $email, $password) {
    try {
        $sql = "CALL LOGIN_ACCOUNT(?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->bindParam(2, $password, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Errore di login: ' . $e->getMessage());
        return null; // o gestire l'errore in modo diverso
    }
}

$loginError = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $output = attempt_login($pdo, $email, $password);

    if ($output) {
        $_SESSION['user'] = [
            'logged' => true,
            'nome' => $output['NOME'],
            'tipoAccount' => $output['TIPO_ACCOUNT'],
        ];
        echo '<script>alert("Accesso eseguito con successo!"); window.location.href = "dashboard.php";</script>';
        exit();
    } else {
        $loginError = "Email o password errata.";
    }
}
