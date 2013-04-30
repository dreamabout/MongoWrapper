<?php


namespace Dreamabout\Mongo\Query;

use PHPUnit_Framework_TestCase;
class QueryTest extends PHPUnit_Framework_TestCase
{

    static public function emptyMethodNames()
    {
        return array(
            array("Sort"),
            array("Skip"),
            array("Limit"),
            array("BatchSize")
        );
    }

    public function testEmptyConstructor()
    {
        $query = new Query();

        $this->assertEmpty($query->getQuery(), "a query without an argument should be empty");
        $this->assertArrayHasKey("_id", $query->getFields(), "_id is the default field");
    }

    public function testConstructorWithSimpleQuery()
    {
        $query = new Query(array("field.subfield" => "hello"));

        $this->assertArrayHasKey("field.subfield", $query->getQuery());
    }

    /**
     * @param $methodName
     * @dataProvider emptyMethodNames
     *
     */
    public function testEmpty($methodName)
    {
        $query = new Query();
        $this->assertFalse($query->has($methodName));
        $this->assertNull($query->{"get{$methodName}"}());
    }

}
