<?php

namespace GitIndexer\Hydrator;

class Commit
{
    /**
     * @var \GitIndexer\Commit
     */
    private $commit;

    /**
     * @var bool
     */
    private $isMessage = false;

    /**
     * @var string
     */
    private $message = '';

    /**
     * @var bool
     */
    private $isDiff = false;

    /**
     * @var string
     */
    private $lineString = '';

    /**
     * @var int
     */
    private $pos = 0;

    /**
     * @return \GitIndexer\Hydrator\Commit
     */
    private function tryHashParse() : Commit
    {
        if ($this->pos !== 0) {
            return $this;
        }

        $this->commit->setHash(substr($this->lineString, 7));

        return $this;
    }

    /**
     * @return \GitIndexer\Hydrator\Commit
     */
    private function tryAuthorParse() : Commit
    {
        if ($this->pos !== 1) {
            return $this;
        }

        $this->commit->setAuthor(substr($this->lineString, 8));

        return $this;
    }

    /**
     * @return \GitIndexer\Hydrator\Commit
     */
    private function tryDate() : Commit
    {
        if ($this->pos !== 2) {
            return $this;
        }

        $this->commit->setDate(new \DateTimeImmutable(substr($this->lineString, 8)));

        return $this;
    }

    /**
     * @return \GitIndexer\Hydrator\Commit
     */
    private function tryMessage() : Commit
    {
        if ($this->pos === 3) {
            $this->isMessage = true;
            return $this;
        }

        if ($this->isMessage !== true) {
            return $this;
        }

        if (strpos($this->lineString, 'diff', 0) === 0) {
            $this->isMessage = false;
            $this->isDiff = true;
            $this->commit->setMessage(trim($this->message));
            $this->message = null;
            return $this;
        }

        $this->message .= trim($this->lineString) . PHP_EOL;

        return $this;
    }

    /**
     * @return \GitIndexer\Hydrator\Commit
     */
    private function tryDiff() : Commit
    {
        if ($this->isDiff !== true) {
            return $this;
        }

        $this->message .= $this->lineString . PHP_EOL;

        return $this;
    }

    /**
     * Hydrate a single commit model from a commit message
     *
     * @see \GitIndexer\tests\units\Hydrator\Commit::testHydrate
     *
     * @param \GitIndexer\Commit $commit
     * @param string $rawCommit
     * @return \GitIndexer\Commit
     */
    public function hydrate(\GitIndexer\Commit $commit, string $rawCommit) : \GitIndexer\Commit
    {
        $this->commit = $commit;
        foreach (explode(PHP_EOL, $rawCommit) as $pos => $lineString) {
            $this->pos        = $pos;
            $this->lineString = $lineString;
            $this
                ->tryHashParse()
                ->tryAuthorParse()
                ->tryDate()
                ->tryMessage()
                ->tryDiff()
            ;
        }

        $this->commit->setDiff(trim($this->message));
        $this->message = '';

        return $commit;
    }
}