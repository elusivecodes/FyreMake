<?php
declare(strict_types=1);

namespace Fyre\Make\Commands;

use Fyre\Command\Command;
use Fyre\Console\Console;
use Fyre\Make\Make;
use Fyre\Migration\MigrationRunner;
use Fyre\Utility\Path;

use function date;
use function file_exists;

/**
 * MakeMigrationCommand
 */
class MakeMigrationCommand extends Command
{
    protected string|null $alias = 'make:migration';

    protected string $description = 'This command will generate a new migration.';

    protected string|null $name = 'Make Migration';

    /**
     * Run the command.
     *
     * @param array $arguments The command arguments.
     * @return int|null The exit code.
     */
    public function run(array $arguments = []): int|null
    {
        $version = $arguments[0] ?? null;
        $namespace = $arguments['namespace'] ?? MigrationRunner::getNamespace() ?? 'App\Migrations';

        if (!$version) {
            $version = date('Ymd');
        }

        $version = (int) $version;

        $migration = 'Migration_'.$version;

        [$namespace, $className] = Make::parseNamespaceClass($namespace, $migration);

        $path = Make::findPath($namespace);

        if (!$path) {
            Console::error('Namespace path not found.');

            return static::CODE_ERROR;
        }

        $fullPath = Path::join($path, $className.'.php');

        if (file_exists($fullPath)) {
            Console::error('Migration file already exists.');

            return static::CODE_ERROR;
        }

        $contents = Make::loadStub('migration', [
            '{namespace}' => $namespace,
            '{class}' => $className,
        ]);

        if (!Make::saveFile($fullPath, $contents)) {
            Console::error('Migration file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
