<?php


namespace GitIndexer\Extractor;


class Commit
{
    public function extract(\GitIndexer\Commit $commit) : array
    {
        return [
            'author'   => $commit->getAuthor(),
            'date'     => $commit->getDate()->format(\DateTime::ISO8601),
            'branches' => $commit->getBranches(),
            'message'  => json_encode($commit->getMessage()),
            'diff'     => json_encode($commit->getDiff()),
        ];
    }
}