<?php

namespace GitIndexer;

class Commit
{
    /**
     * @var string
     */
    private $hash;

    /**
     * @var string
     */
    private $author;

    /**
     * @var string
     */
    private $message;

    /**
     * @var \ArrayObject
     */
    private $modifiedFiles;

    /**
     * @var string
     */
    private $diff;

    /**
     * @var \DateTimeImmutable
     */
    private $date;

    /**
     * Commit constructor.
     */
    public function __construct()
    {
        $this->modifiedFiles = new \ArrayObject;
    }

    /**
     * @return string
     */
    public function getHash() : string
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     * @return \GitIndexer\Commit
     */
    public function setHash(string $hash) : Commit
    {
        $this->hash = $hash;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthor() : string
    {
        return $this->author;
    }

    /**
     * @param string $author
     * @return \GitIndexer\Commit
     */
    public function setAuthor(string $author) : Commit
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage() : string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return \GitIndexer\Commit
     */
    public function setMessage(string $message) : Commit
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return \ArrayObject
     */
    public function getModifiedFiles() : \ArrayObject
    {
        return $this->modifiedFiles;
    }

    /**
     * @return string
     */
    public function getDiff() : string
    {
        return $this->diff;
    }

    /**
     * @param string $diff
     * @return \GitIndexer\Commit
     */
    public function setDiff(string $diff) : Commit
    {
        $this->diff = $diff;
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate() : \DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @param \DateTimeImmutable $date
     * @return \GitIndexer\Commit
     */
    public function setDate(\DateTimeImmutable $date)
    {
        $this->date = $date;
        return $this;
    }
}