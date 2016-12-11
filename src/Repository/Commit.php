<?php


namespace GitIndexer\Repository;

use GitIndexer\Gateway\Commit\Cli;
use GitIndexer\Hydrator\Commit as CommitHydrator;

class Commit
{
    /**
     * @var \GitIndexer\Gateway\Commit\Cli
     */
    private $gateway;

    /**
     * @var \GitIndexer\Hydrator\Commit
     */
    private $hydrator;

    /**
     * Commit constructor.
     * @param \GitIndexer\Gateway\Commit\Cli $gateway
     * @param \GitIndexer\Hydrator\Commit $hydrator
     */
    public function __construct(Cli $gateway, CommitHydrator $hydrator)
    {
        $this->gateway = $gateway;
        $this->hydrator = $hydrator;
    }

    /**
     * Generate commits from the repository set in the gateway.
     *
     * @return \Generator
     */
    public function fetchCommits() : \Generator
    {
        foreach ($this->gateway->fetchAllCommits() as $hash) {
            try {
                yield $this->hydrator->hydrate(
                    (new \GitIndexer\Commit)->setBranches($this->gateway->fetchCommitBranches($hash)),
                    $this->gateway->fetchCommit($hash)
                );
            } catch (\Exception $e) {
                // TODO : Do something with exception
                continue;
            }
        }
    }
}