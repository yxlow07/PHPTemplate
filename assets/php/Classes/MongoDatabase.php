<?php

namespace auth;
use \MongoDB as md;
include_once dirname(__DIR__) . "/config.php";

class Database
{
    public function __construct(string $db, string $collection_name){
        $collection = (new md\Client)->$db->$collection_name;
        $this->run($collection);
    }

    private function run($collection): void
    {
        $insertOneResult = $collection->insertOne([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'name' => 'Admin User',
        ]);

        printf("Inserted %d document(s)\n", $insertOneResult->getInsertedCount());

        var_dump($insertOneResult->getInsertedId());
    }
}