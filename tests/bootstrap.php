<?php
declare(strict_types=1);

use Fyre\Command\CommandRunner;
use Fyre\Config\Config;
use Fyre\Entity\EntityLocator;
use Fyre\Lang\Lang;
use Fyre\Loader\Loader;
use Fyre\Migration\MigrationRunner;
use Fyre\ORM\BehaviorRegistry;
use Fyre\ORM\ModelRegistry;
use Fyre\Utility\Path;
use Fyre\View\CellRegistry;
use Fyre\View\HelperRegistry;
use Fyre\View\Template;

$tmpDir = Path::normalize(__DIR__.'/../tmp');

Loader::addNamespaces([
    'Example\\' => Path::join($tmpDir),
]);

Config::addPath(Path::join($tmpDir, 'config'));
Lang::addPath(Path::join($tmpDir, 'lang'));
Template::addPath(Path::join($tmpDir, 'templates'));

CommandRunner::addNamespace('Example\Commands');
CellRegistry::addNamespace('Example\Cells');
EntityLocator::addNamespace('Example\Entities');
HelperRegistry::addNamespace('Example\Helpers');
MigrationRunner::setNamespace('Example\Migrations');
ModelRegistry::addNamespace('Example\Models');
BehaviorRegistry::addNamespace('Example\Models\Behaviors');

Lang::setDefaultLocale('en');
