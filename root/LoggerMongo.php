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
            // Removed echo statement
        } else {
            // Collection already exists
            // Removed echo statement
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
            // Handle any errors during log insertion
            error_log('Error during log insertion: ' . $e->getMessage());
        }
    }
}
?>
