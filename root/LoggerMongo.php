<?php
// LoggerMongo.php
require 'vendor/autoload.php';

class LoggerMongo
{
    private $client;
    private $database;
    private $collection;

    public function __construct($mongoConnection, $databaseName)
    {
        $this->client = new MongoDB\Client($mongoConnection);
        $this->database = $this->client->{$databaseName};
        $this->collection = $this->database->eventLog;

        $this->initializeCollection();
    }

    private function initializeCollection()
    {
        $collectionName = 'eventLog';

        $collections = $this->database->listCollections(['filter' => ['name' => $collectionName]]);
        $count = iterator_count($collections);

        if ($count === 0) {
            // Collection doesn't exist, create it
            $this->database->createCollection($collectionName);
            echo "Collection '$collectionName' created successfully.\n";
        } else {
            // Collection already exists
            echo "Collection '$collectionName' already exists.\n";
        }
    }

    public function logEvent($eventType, $message)
    {
        try {
            $logEntry = [
                'timestamp' => time(),
                'event_type' => $eventType,
                'message' => $message,
            ];

            $this->collection->insertOne($logEntry);
        } catch (Exception $e) {
            // Gestisci eventuali errori durante l'inserimento del log
            error_log('Errore durante l\'inserimento del log: ' . $e->getMessage());
        }
    }
}
?>
