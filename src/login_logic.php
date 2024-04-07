<?php
// login_logic.php
session_start();
require_once __DIR__ . '/root/connect.php'; 

// Includi la classe LoggerMongo
require_once __DIR__ . '../root/LoggerMongo.php';

function attempt_login($pdo, $email, $password) {
    // Inizializza il logger all'esterno del blocco try
    $logger = new LoggerMongo("mongodb://localhost:27017", "logDB");

    try {
        $sql = "CALL LOGIN_ACCOUNT(?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->bindParam(2, $password, PDO::PARAM_STR);
        $stmt->execute();
        $isValidLogin = $stmt->fetchColumn(); 

        if (!$isValidLogin) {
            // Logga il tentativo di login fallito
            $logger->logEvent('FailedLoginAttempt', "Tentativo di login fallito per l'utente $email");

            // Mantieni anche il vecchio sistema di logging
            error_log('Errore di login: Tentativo di login fallito per l\'utente ' . $email);
            
            return false; 
        }

        // Login riuscito, logga l'evento
        $logger->logEvent('SuccessfulLogin', "Login eseguito con successo per l'utente $email");

        $_SESSION['user'] = [
            'logged' => true,
            'email' => $email, 
        ];

        return true;  
    } catch (PDOException $e) {
        // Logga l'errore di login
        $logger->logEvent('ErrorLogin', 'Errore di login: ' . $e->getMessage());

        // Mantieni anche il vecchio sistema di logging
        error_log('Errore di login: ' . $e->getMessage());
        
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (attempt_login($pdo, $email, $password)) {
        echo '<script>alert("Accesso eseguito con successo! Verrai reinderizzato alla dashboard"); window.location.href = "dashboard.php";</script>';
        exit();
    } else {
        $loginError = "Errore durante l'accesso. Assicurati che tutti i dati di accesso siano corretti."; 
    }
}
?>
