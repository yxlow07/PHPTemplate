<?php
namespace auth;

use \MongoDB\Client;
use \MongoDB\Collection;
use MongoDB\Model\BSONDocument as BSONDoc;

include_once dirname(__DIR__) . "/config.php";

class MongoDatabase
{
    private Client $client;
    private Collection $collection;
    protected array $defaultDB = ["admin", "config", "local"];

    public function __construct(
        private readonly string $db_name,
        private readonly string $collection_name
    ){
        $this->client = new Client;
        $this->collection = $this->client->{$this->db_name}->{$this->collection_name};
    }

    public function insert(array $data) : string
    {
        if ($this->validate()) {
            $res = $this->collection->insertOne($data);
            return $res->isAcknowledged() ? (string) $res->getInsertedId(): "Database insert failed";
        }
        return "Database failed";
    }

    public function find(array $data) : string|null|BSONDoc
    {
        if ($this->validate()) {
            return $this->collection->findOne($data);
        }
        return "Database failed";
    }

    protected function validate(): bool
    {
        $db = $this->db_name;
        if (!$this->checkAvailabilityOfDB($db) && !in_array($db, $this->defaultDB)) {
            exit("Database $db not found");
        }
        return true;
    }

    protected function checkAvailabilityOfDB($database_name) : bool
    {
        $databases = $this->client->listDatabases();
        foreach ($databases as $database) {
            if ($database_name === $database["name"]) {
                return true;
            }
        }
        return false;
    }
}