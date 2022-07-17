<?php
namespace app\db;

use app\validation\ValidationUtility;
use JetBrains\PhpStorm\NoReturn;
use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Driver\Cursor;
use MongoDB\Model\BSONDocument as BSONDoc;
use MongoDB\UpdateResult;

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

    public function find(array $data, bool $findOne = true): string|null|BSONDoc|Cursor
    {
        if ($this->validate()) {
            return $findOne ? $this->collection->findOne($data) : $this->collection->find(...$data);
        }
        return "Database failed";
    }

    public function update(array $find, array $data): UpdateResult
    {
        return $this->collection->updateOne(
            $find,
            ['$set' => $data]
        );
    }

    protected function validate(): bool
    {
        $db = $this->db_name;
        if (!$this->checkAvailabilityOfDB($db) && !in_array($db, $this->defaultDB)) {
            exit("Database $db not found");
        }
        return true;
    }

    protected function checkAvailabilityOfDB($database_name): bool
    {
        $databases = $this->client->listDatabases();
        foreach ($databases as $database) {
            if ($database_name === $database["name"]) {
                return true;
            }
        }
        return false;
    }

    #[NoReturn]
    public static function checkUpdatedStatus(UpdateResult $result): void
    {
        if ($result->getMatchedCount() < 1) {
            ValidationUtility::returnJson(["status" => false, "msg" => "Your account doesn't exist"]);
        }
        if (!$result->isAcknowledged()) {
            ValidationUtility::returnJson(["status" => false, "msg" => "Your update is not acknowledged"]);
        }
        if ($result->getModifiedCount() < 1) {
            ValidationUtility::returnJson(["status" => false, "msg" => "Nothing is changed"]);
        }
        ValidationUtility::returnJson(["status" => true, "msg" => "Successfully updated your profile"]);
    }

    public function getItemCount(): int
    {
        return $this->collection->countDocuments();
    }
}