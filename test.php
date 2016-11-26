<?php

$output = [];
echo exec('git log', $output);

var_dump($output);
