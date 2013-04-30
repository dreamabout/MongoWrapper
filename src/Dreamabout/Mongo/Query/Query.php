<?php


namespace Dreamabout\Mongo\Query;


class Query
{
    private $batchSize;
    private $fields;
    private $limit;
    private $query;
    private $skip;
    private $sort;

    public function __construct($query = array())
    {
        $this->query = $query;
        $this->fields(array("_id" => true));

        return $this;
    }

    public function addField($fieldName, $include = true)
    {
        $this->fields[$fieldName] = $include;

        return $this;
    }

    public function byId($id)
    {
        if (!($id instanceof \MongoId)) {
            $id = new \MongoId($id);
        }

        $this->query["_id"] = $id;

        return $this;
    }

    public function getBatchSize()
    {
        return $this->batchSize;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function fields($fields)
    {
        $this->fields = $fields;

        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function getSkip()
    {
        return $this->skip;
    }

    public function getSort()
    {
        return $this->sort;
    }

    public function skip($skip)
    {
        $this->skip = $skip;

        return $this;
    }

    public function sort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    public function has($field)
    {
        $field = strtolower($field);
        return isset($this->{$field}) && !empty($this->{$field});
    }

}
