<?php


namespace Dreamabout\Mongo;


class Connection
{

    private $client;
    private $database;

    public function __construct($host, $database, $options, $debug = true)
    {
        $this->client = new \MongoClient($host, $options);
        $this->database = $database;
        if ($debug) {
            \MongoLog::setLevel(\MongoLog::ALL);
            \MongoLog::setModule(\MongoLog::ALL);
        }

        return $this;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getCollection($name)
    {
        return $this->client->selectCollection($this->database, $name);
    }

    public function setDatabase($name)
    {
        $this->database = $name;
    }

    public function getDatabase()
    {
        return $this->database;
    }
}
