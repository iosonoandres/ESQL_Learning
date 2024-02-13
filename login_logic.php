<?php
session_start();
require_once __DIR__ . '/root/connect.php'; 

function attempt_login($pdo, $email, $password) {
    try {
        $sql = "CALL LOGIN_ACCOUNT(?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->bindParam(2, $password, PDO::PARAM_STR);
        $stmt->execute();
        $isValidLogin = $stmt->fetchColumn(); 

        if (!$isValidLogin) {
            return false; 
        }

        $_SESSION['user'] = [
            'logged' => true,
            'email' => $email, 
        ];

        return true;  
    } catch (PDOException $e) {
        error_log('Errore di login: ' . $e->getMessage());
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (attempt_login($pdo, $email, $password)) {
        echo '<script>alert("Accesso eseguito con successo!"); window.location.href = "dashboard.php";</script>';
        exit();
    } else {
        $loginError = "Errore durante l'accesso. Assicurati che tutti i dati di accesso siano corretti."; 
    }
}
?>
