<?php


namespace Dreamabout\Mongo\Document;


class Document implements DocumentInterface
{
    private $dreamaboutOriginal;
    private $id;

    public function getId()
    {
        return $this->id;
    }


    /**
     * @param array $data The data to consist of.
     * @return bool if the data is valid, true, if not false
     */
    public function build($data)
    {
        $this->dreamaboutOriginal = $data;

        if (isset($data["_id"])) {
            $this->id = $data["_id"];
        }
    }

    /**
     * Compares two objects, and returns a mongo update query
     * for updating the current document to match the other document
     *
     * It compares in the following order:
     *  - if id's is not equal, return null
     *  - compare the rest. Subdocuments should also implement this interface
     *
     * @param DocumentInterface $other
     *
     * @return array|null
     */
    public function diff(DocumentInterface $other)
    {
        // TODO: Implement diff() method.
    }
}
