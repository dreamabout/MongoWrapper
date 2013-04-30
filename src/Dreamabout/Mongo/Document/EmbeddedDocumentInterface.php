<?php


namespace Dreamabout\Mongo\Document;


interface EmbeddedDocumentInterface extends DocumentInterface {
    public function setParent(DocumentInterface $parent);
}
