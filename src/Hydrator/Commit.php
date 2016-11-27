<?php


namespace GitIndexer\Hydrator;


class Commit
{
    public function hydrate(\GitIndexer\Commit $commit, string $rawCommit) : \GitIndexer\Commit
    {
        return $commit;
    }
}