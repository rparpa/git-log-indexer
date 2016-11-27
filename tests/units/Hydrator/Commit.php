<?php

namespace GitIndexer\tests\units\Hydrator;

use atoum;

class Commit extends atoum
{
    public function beforeTestMethod($testMethod)
    {
        parent::beforeTestMethod($testMethod);
        $this->getMockGenerator()->allIsInterface();
    }

    protected function hydrateDataProvider()
    {
        $commit = <<<'COMMIT'
commit d59cd2e11728abd62ccf60de7ce18f08152edf6c
Author: Rémi Parpaillon <remi.parpaillon@gmail.com>
Date:   Sat Nov 26 17:42:26 2016 +0100

    Add basic test file

    At the moment, only a manual test.  This commit is unsigned on purposes, in
    order to test non signed commit parsing.

diff --git a/test.php b/test.php
new file mode 100644
index 0000000..56dc498
--- /dev/null
+++ b/test.php
@@ -0,0 +1,6 @@
+<?php
+
+$output = [];
+echo exec('git log', $output);
+
+var_dump($output);
COMMIT;

        $commitMessage = <<<'MSG'
Add basic test file

    At the moment, only a manual test.  This commit is unsigned on purposes, in
    order to test non signed commit parsing.
MSG;

        $diff = <<<'DIFF'
diff --git a/test.php b/test.php
new file mode 100644
index 0000000..56dc498
--- /dev/null
+++ b/test.php
@@ -0,0 +1,6 @@
+<?php
+
+$output = [];
+echo exec('git log', $output);
+
+var_dump($output);
DIFF;



        yield [
            $commit,
            'd59cd2e11728abd62ccf60de7ce18f08152edf6c',
            'Rémi Parpaillon <remi.parpaillon@gmail.com>',
            '2016-11-26T17:42:26+01:00',
            $commitMessage,
            $diff
        ];
    }

    public function testHydrate(\GitIndexer\Commit $commit)
    {
        foreach ($this->hydrateDataProvider() as $dataToTest) {
            $this
                ->object($this->newTestedInstance())
                    ->isTestedInstance()

                ->object($this->testedInstance->hydrate($commit, $dataToTest[0]))
                    ->isIdenticalTo($commit)

                ->mock($commit)
                    ->call('setHash')
                    ->withArguments($dataToTest[1])
                    ->once()

                    ->call('setAuthor')
                    ->withArguments($dataToTest[2])
                    ->once()

                    ->call('setDate')
                    ->withArguments(new \DateTime($dataToTest[3]))
                    ->once()

                    ->call('setMessage')
                    ->withArguments($dataToTest[4])
                    ->once()

                    ->call('setDiff')
                    ->withArguments($dataToTest[5])
                    ->once()
            ;
        }
    }
}