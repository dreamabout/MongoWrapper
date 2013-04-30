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

        Phake::when($this->emptyQuery)->getQuery()->thenReturn(array("field.subfield" => "hello"));
        Phake::when($this->emptyQuery)->getFields()->thenReturn(array("_id" => true, "field" => true));

        $this->factory = Phake::mock("Dreamabout\\Mongo\\Document\\DocumentFactoryInterface");
        $this->mongoCollection = Phake::mock("MongoCollection");

        $this->connection = Phake::mock("Dreamabout\\Mongo\\Connection");
        Phake::when($this->connection)->getCollection(Phake::anyParameters())->thenReturn($this->mongoCollection);

        $this->mongoCursor = Phake::mock("MongoCursor");

        Phake::when($this->mongoCollection)->find(Phake::anyParameters())->thenReturn($this->mongoCursor);


        $this->repository = new ConcreteRepository($this->connection, $this->factory);
    }

    public function testFind()
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

}
