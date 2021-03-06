<?php


namespace Dreamabout\Mongo\Document;


interface DocumentInterface
{
    /**
     * @param array $data The data to consist of.
     * @return bool if the data is valid, true, if not false
     */
    public function build($data);

    /**
     * Compares two objects, and returns a mongo update query
     * for updating the current document to match the other document
     *
     * It compares in the following order:
     *  - Ignore if _id is not equal
     *  - compare the rest. Subdocuments should also implement this interface
     *
     * @param DocumentInterface $other
     *
     * @return array|null
     */

    public function diff(DocumentInterface $other);

    /**
     * @return \MongoId
     */
    public function getId();
}
