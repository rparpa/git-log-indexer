<?php

/**
 * @var \mageekguy\atoum\runner $runner
 * @var \mageekguy\atoum\scripts\runner $script
 */

$testGenerator = new mageekguy\atoum\test\generator();

$testGenerator->setTestClassesDirectory(__DIR__ . '/tests/units');

// le namespace du test unitaire.
$testGenerator->setTestClassNamespace('GitIndexer\tests\units');

$runner->addTestsFromDirectory(__DIR__ . '/tests/units');

$extension = new mageekguy\atoum\autoloop\extension($script);
$extension
    ->setWatchedFiles([__DIR__ . '/src'])
    ->addToRunner($runner);

$script->getRunner()->setTestGenerator($testGenerator);
