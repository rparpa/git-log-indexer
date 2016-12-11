<?php


namespace GitIndexer\Gateway\Commit;

class Cli
{
    /**
     * @var string
     */
    private $gitCmd = 'git';

    /**
     * @var string
     */
    private $tmpDir;

    private $extensionInDiff = '*.php';

    /**
     * Cli constructor.
     * @param string $repositoryPath
     * @param string $tmpDir
     */
    public function __construct(string $repositoryPath, string $tmpDir)
    {
        if (is_dir($repositoryPath)) {
            $this->gitCmd .= ' -C ' . $repositoryPath;
        }

        $this->tmpDir = $tmpDir;
        shell_exec($this->makeCmd('fetch --all -q'));
    }

    private function makeCmd(string $cmd) : string
    {
        return $this->gitCmd . ' ' . $cmd;
    }


    /**
     * Get all commit information
     *
     * @see \GitIndexer\tests\units\Gateway\Commit\Cli::testFetchCommit
     * @see \GitIndexer\tests\units\Gateway\Commit\Cli::testFetchCommitEmpty
     *
     * @param string $hash
     * @return string
     * @throws \Exception
     */
    public function fetchCommit(string $hash) : string
    {
        $extensions = $this->extensionInDiff;

        if($returned = shell_exec($this->makeCmd("show $hash" . ' ' . "'$extensions'"))) {
            return $returned;
        } else {
            throw new \RuntimeException("No data for commit $hash", 404);
        }
    }

    /**
     * Fetch all branches where this commit commit exists
     *
     * @see \GitIndexer\tests\units\Gateway\Commit\Cli::testFetchCommitBranches
     *
     * @param string $hash
     * @return string[]
     */
    public function fetchCommitBranches(string $hash) : array
    {
        $branches = [];
        $output   = [];
        exec($this->makeCmd("branch --remotes --contains $hash"), $output);
        foreach ($output as $branch) {
            if (strpos($branch, '/HEAD') > 0) {
                continue;
            }
            $branches[] = $branch;
        }

        return $branches;
    }

    /**
     * Fetch all commit hash as a generator, as the list can be pretty large
     *
     * @see \GitIndexer\tests\units\Gateway\Commit\Cli::testFetchAllCommitsFileError
     * @see \GitIndexer\tests\units\Gateway\Commit\Cli::testFetchAllCommits
     *
     * @return \Generator
     */
    public function fetchAllCommits()
    {
        $logPath = $this->tmpDir . DIRECTORY_SEPARATOR . 'commit-list.git-indexer';
        exec($this->makeCmd("rev-list --remotes --no-merges > $logPath"));

        $file = @fopen($logPath, 'r');

        if (!$file) {
            return;
        }

        while($hash = fgets($file)) {
            yield trim($hash);
        }
    }
}