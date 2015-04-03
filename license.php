#!/usr/bin/env php
<?php

/**
 * License CLI
 */

// Load the composer autoload
require_once __DIR__ . '/vendor/autoload.php';

// Create a new application, assign command(s) and run it
use Symfony\Component\Console\Application;
use Aedart\License\File\Manager\Console\CopyLicenseCommand;

$app = new Application('Aedart License File Manager', '1');
$app->add(new CopyLicenseCommand());
$app->run();