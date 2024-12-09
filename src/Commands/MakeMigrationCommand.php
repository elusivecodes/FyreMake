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

    protected string $description = 'Generate a new migration.';

    protected array $options = [
        'version' => [],
        'namespace' => [],
    ];

    /**
     * Run the command.
     *
     * @param Make $make The Make.
     * @param MigrationRunner $migrationRunner The MigrationRunner.
     * @param Console $io The Console.
     * @param string|null $version The migration version.
     * @param string|null $namespace The migration namespace.
     * @return int|null The exit code.
     */
    public function run(Make $make, MigrationRunner $migrationRunner, Console $io, string|null $version = null, string|null $namespace = null): int|null
    {
        $version ??= date('Ymd');
        $namespace ??= $migrationRunner->getNamespace() ?? 'App\Migrations';

        $version = (int) $version;

        $migration = 'Migration_'.$version;

        [$namespace, $className] = Make::parseNamespaceClass($namespace, $migration);

        $path = $make->findPath($namespace);

        if (!$path) {
            $io->error('Namespace path not found.');

            return static::CODE_ERROR;
        }

        $fullPath = Path::join($path, $className.'.php');

        if (file_exists($fullPath)) {
            $io->error('Migration file already exists.');

            return static::CODE_ERROR;
        }

        $contents = Make::loadStub('migration', [
            '{namespace}' => $namespace,
            '{class}' => $className,
        ]);

        if (!Make::saveFile($fullPath, $contents)) {
            $io->error('Migration file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
