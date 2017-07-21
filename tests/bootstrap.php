<?php

require_once __DIR__ . '/../app/autoload.php';

use AppBundle\Command\TestFixturesLoaderCommand;

$kernel = new AppKernel('test', true);
$kernel->boot();

TestFixturesLoaderCommand::runCommand($kernel, ['command' => 'test:fixtures:load', '-e' => 'test']);
