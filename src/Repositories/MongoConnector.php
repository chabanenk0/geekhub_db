<?php

namespace Repositories;

class MongoConnector
{
    private $mongoConnection;

    private $defaultDatabaseName;

    /**
     * StudentsRepository constructor.
     * Initialize the database connection with sql server via given credentials
     * @param string $defaultDatabaseName
     */
    public function __construct($defaultDatabaseName = 'test')
    {
        $this->mongoConnection = new \MongoClient();
        if (!$this->mongoConnection) {
            return false;
            //throw new Exception('Error connecting to the database');
        }
        $this->defaultDatabaseName = $defaultDatabaseName;

    }

    /**
     * @param string|null $databaseName
     * @return mixed
     */
    public function getDatabase($databaseName = null)
    {
        if (!$databaseName) {
            $databaseName = $this->defaultDatabaseName;
        }
        return $this->mongoConnection->$databaseName;
    }

    /**
     * @param string $collectionName
     * @param string null $databaseName
     * @return mixed
     * @throws \Exception
     */
    public function getCollection($collectionName, $databaseName = null) {
        $database = $this->getDatabase($databaseName);
        if (!$database) {
            throw new \Exception('Unable to get mongo database ' . $databaseName);
        }

        return $database->$collectionName;
    }
}