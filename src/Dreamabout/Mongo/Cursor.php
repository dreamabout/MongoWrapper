<?php


namespace Dreamabout\Mongo;


use Dreamabout\Mongo\Document\DocumentFactoryInterface;

class Cursor implements \Iterator, \Countable
{
    private $cursor;
    private $factory;
    private $repository;
    /** @var Document\DocumentInterface */
    private $current;

    public function __construct(\MongoCursor $cursor, DocumentFactoryInterface $factory, Repository $repository)
    {
        $this->cursor = $cursor;
        $this->factory = $factory;
        $this->repository = $repository;

        $this->getCursor()->reset();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return Document\DocumentInterface
     */
    public function current()
    {
        $this->current = $this->factory->build($this->key(), $this->getCursor()->current());

        return $this->current;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->current->__destroy();

        return $this->getCursor()->next();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return string
     */
    public function key()
    {
        return $this->getCursor()->key();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     *       Returns true on success or false on failure.
     */
    public function valid()
    {
        return $this->cursor->valid();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->getCursor()->rewind();

        return $this;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     *       The return value is cast to an integer.
     */
    public function count()
    {
        return $this->getCursor()->count();
    }

    public function getCursor()
    {
        return $this->cursor;
    }

    public function __call($method, $params)
    {
        if ($this->getCursor() instanceof MongoCursor) {
            return call_user_func_array(array($this->cursor(), $method), $params);
        }
        trigger_error("Call to undefined function {$method} on the cursor");
    }
}
