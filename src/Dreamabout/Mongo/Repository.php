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
        $cursor = $this->createCursor($cursor);

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

    private function createCursor(\MongoCursor $cursor)
    {
        return new Cursor($cursor, $this->factory, $this);
    }

    /**
     * @return string collection name for the repository
     */
    abstract public function getCollectionName();
}
