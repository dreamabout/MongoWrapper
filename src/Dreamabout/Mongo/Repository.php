<?php


namespace Dreamabout\Mongo;


use Dreamabout\Mongo\Document\DocumentFactoryInterface;
use Dreamabout\Mongo\Query\Query;

abstract class Repository
{
    private $collection;
    private $connection;
    private $factory;

    public function __construct(Connection $connection, DocumentFactoryInterface $factory)
    {
        $this->connection = $connection;
        $this->factory = $factory;
    }

    public function getCollection()
    {
        if (!$this->collection) {
            $this->collection = $this->connection->getCollection($this->getCollectionName());
        }

        return $this->collection;
    }

    public function getConnection()
    {
        return $this->connection->getClient();
    }

    public function find(Query $query)
    {
        $cursor = $this->getCollection()->find($query->getQuery(), $query->getFields());
        $cursor = $this->createCursor($cursor, $query);

        return $cursor;
    }

    public function findOne(Query $query)
    {
        $data = $this->getCollection()->findOne($query->getQuery(), $query->getFields());
        if (isset($data["_id"])) {
            return $this->factory->build($data["_id"], $data);
        }

        return null;
    }

    private function createCursor(\MongoCursor $cursor, Query $query)
    {
        $cursor = new Cursor($cursor, $this->factory, $this);

        if ($query->has("sort")) {
            $cursor->sort($query->getSort());
        }
        if ($query->has("limit")) {
            $cursor->limit($query->getLimit());
        }
        if ($query->has("skip")) {
            $cursor->skip($query->getSkip());
        }

        return $cursor;
    }

    /**
     * @return string collection name for the repository
     */
    abstract public function getCollectionName();
}
