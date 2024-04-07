<?php
// connect.php
try{
    $pdo = new PDO('mysql:host=localhost; dbname=dbesql', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    echo("[ERRORE] Connessione al DB non riuscita. Errore: ".$e->getMessage());
    exit();
}

?>