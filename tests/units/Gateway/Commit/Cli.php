<?php


namespace GitIndexer\tests\units\Gateway\Commit;

use atoum;

class Cli extends atoum
{
    private $tmpDir = __DIR__ . '/../../../tmp';

    private $filePath = '';

    /**
     * @param array ...$arguments
     *
     * @return \GitIndexer\Gateway\Commit\Cli
     */
    public function newTestedInstance(...$arguments)
    {
        return parent::newTestedInstance($this->tmpDir, realpath($this->tmpDir));
    }

    public function beforeTestMethod($testMethod)
    {
        parent::beforeTestMethod($testMethod);
        $this->filePath = $this->tmpDir . '/commit-list.git-indexer';
        $this->function->shell_exec = '';
        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }
    }

    /**
     * @see \GitIndexer\Gateway\Commit\Cli::fetchCommit
     */
    public function testFetchCommit()
    {
        $this
            ->object($this->newTestedInstance())
                ->isTestedInstance()

            ->given($this->function->shell_exec = 'my commit info')
            ->then()
                ->string($this->testedInstance->fetchCommit('abcd1'))
                    ->isIdenticalTo('my commit info')
        ;
    }

    /**
     * @see \GitIndexer\Gateway\Commit\Cli::fetchCommit
     */
    public function testFetchCommitEmpty()
    {
        $this
            ->object($this->newTestedInstance())
                ->isTestedInstance()

            ->given($this->function->shell_exec = '')
            ->then()
                ->exception(function () {
                    $this->testedInstance->fetchCommit('abcd1');
                })
                    ->isInstanceOf(\RuntimeException::class)
                    ->hasMessage('No data for commit abcd1')
                    ->hasCode(404)
        ;
    }

    /**
     * @see \GitIndexer\Gateway\Commit\Cli::fetchCommitBranches
     */
    public function testFetchCommitBranches()
    {
        $this
            ->object($this->newTestedInstance())
                ->isTestedInstance()

            ->given($this->function->exec = function ($cmd, &$output) {
                $output = [
                    'origin/master',
                    'origin/develop',
                    'origin/HEAD'
                ];
            })
            ->then()
                ->array($this->testedInstance->fetchCommitBranches('abcd1'))
                    ->isIdenticalTo([
                        'origin/master',
                        'origin/develop'
                    ])
        ;

    }

    /**
     * @see \GitIndexer\Gateway\Commit\Cli::fetchAllCommits
     */
    public function testFetchAllCommits()
    {
        $this
            ->object($this->newTestedInstance())
                ->isTestedInstance()

            ->given($this->function->exec = 0)
            ->and($filePath = $this->filePath)
            ->and(file_put_contents(
                $filePath,
                'abcd1' . PHP_EOL .'abcd2' . PHP_EOL . 'abcd3'
            ))
            ->then()
                ->object($hashes = $this->testedInstance->fetchAllCommits())
                    ->isInstanceOf(\Generator::class)

                ->array($hashes = iterator_to_array($hashes))
                    ->isIdenticalTo([
                        'abcd1',
                        'abcd2',
                        'abcd3'
                    ])
        ;
    }

    /**
     * @see \GitIndexer\Gateway\Commit\Cli::fetchAllCommits
     */
    public function testFetchAllCommitsFileError()
    {

        $this
            ->object($this->newTestedInstance())
                ->isTestedInstance()

            ->given($this->function->exec = 0)
            ->and($this->function->fopen = null)
            ->and($filePath = $this->filePath)
            ->and($testedInstance = $this->testedInstance)
            ->then()
                ->object($hashes = $this->testedInstance->fetchAllCommits())
                    ->isInstanceOf(\Generator::class)
                ->variable($hashes->current())
                    ->isNull()
        ;
    }
}