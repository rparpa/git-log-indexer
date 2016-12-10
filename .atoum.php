<?php

/** @var \mageekguy\atoum\test\generator $testGenerator */
$testGenerator = new atoum\test\generator();

$testGenerator->setTestClassesDirectory(__DIR__ . '/tests/units');

// le namespace du test unitaire.
$testGenerator->setTestClassNamespace('GitIndexer\tests\units');

$runner->addTestsFromDirectory(__DIR__ . '/tests/units');

$script->getRunner()->setTestGenerator($testGenerator);
