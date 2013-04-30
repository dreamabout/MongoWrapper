<?php


namespace Dreamabout\Mongo\Document;


interface DocumentFactoryInterface
{
    /**
     * @param $id
     * @param $data
     * @return DocumentInterface
     */
    public function build($id, $data);
}
