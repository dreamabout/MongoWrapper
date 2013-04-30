<?php


namespace Dreamabout\Mongo;

use Phake;

class ConcreteRepository extends Repository
{
    /**
     * @return string collection name for the repository
     */
    public function getCollectionName()
    {
        return "concrete_repository";
    }

}

class RepositoryTest extends \PHPUnit_Framework_TestCase
{
    private $emptyQuery;
    private $query;
    private $connection;
    private $factory;
    private $repository;

    public function setUp()
    {
        $this->emptyQuery = Phake::mock("Dreamabout\\Mongo\\Query\\Query");

        Phake::when($this->emptyQuery)->getQuery()->thenReturn(array());
        Phake::when($this->emptyQuery)->getFields()->thenReturn(array("_id" => true));

        $this->query = Phake::mock("Dreamabout\\Mongo\\Query\\Query");

        Phake::when($this->query)->getQuery()->thenReturn(array("field.subfield" => "hello"));
        Phake::when($this->query)->getFields()->thenReturn(array("_id" => true, "field" => true));

        $this->factory = Phake::mock("Dreamabout\\Mongo\\Document\\DocumentFactoryInterface");
        $this->mongoCollection = Phake::mock("MongoCollection");

        $this->connection = Phake::mock("Dreamabout\\Mongo\\Connection");
        Phake::when($this->connection)->getCollection(Phake::anyParameters())->thenReturn($this->mongoCollection);

        $this->mongoCursor = Phake::mock("MongoCursor");

        Phake::when($this->mongoCollection)->find(Phake::anyParameters())->thenReturn($this->mongoCursor);
        Phake::when($this->mongoCollection)->findOne(array("field.subfield" => "hello"), array("field" => true, "_id" => true))
            ->thenReturn(array("_id" => new \MongoId(str_repeat("0", 24)), "field" => array("subfield" => "hello")));

        Phake::when($this->factory)->build(new \MongoId(str_repeat("0", 24)), array("_id" => new \MongoId(str_repeat("0", 24)), "field" => array("subfield" => "hello")))
            ->thenReturn(Phake::mock("Dreamabout\\Mongo\\Document\\DocumentInterface"));

        $this->repository = new ConcreteRepository($this->connection, $this->factory);
    }

    public function testEmptyFind()
    {
        $res = $this->repository->find($this->emptyQuery);
        Phake::verify($this->emptyQuery)->getQuery();
        Phake::verify($this->emptyQuery)->getFields();
        Phake::verify($this->emptyQuery, Phake::times(3))->has(Phake::anyParameters());
        Phake::verify($this->emptyQuery, Phake::never())->getSort();
        Phake::verify($this->emptyQuery, Phake::never())->getLimit();
        Phake::verify($this->emptyQuery, Phake::never())->getSkip();

        $this->assertInstanceOf("\\Dreamabout\\Mongo\\Cursor", $res);
    }

    public function testEmptyFindOne()
    {
        $res = $this->repository->findOne($this->emptyQuery);
        Phake::verify($this->emptyQuery)->getQuery();
        Phake::verify($this->emptyQuery)->getFields();
        Phake::verify($this->factory, Phake::never())->build(Phake::anyParameters());
        $this->assertNull($res, "null is the result of no query");
    }

    public function testNonEmptyFindOne()
    {
        $res = $this->repository->findOne($this->query);
        Phake::verify($this->query)->getQuery();
        Phake::verify($this->query)->getFields();
        Phake::verify($this->factory)->build(new \MongoId(str_repeat("0", 24)), array("_id" => new \MongoId(str_repeat("0", 24)), "field" => array("subfield" => "hello")));

        $this->assertInstanceOf("\\Dreamabout\\Mongo\\Document\\DocumentInterface", $res);

    }

}
