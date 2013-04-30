<?php


namespace Dreamabout\Mongo\Document;


interface DocumentGroupInterface extends EmbeddedDocumentInterface, \Iterator, \Traversable {
    public function getIndex($index);
    public function setIndex($index, $data);
}
