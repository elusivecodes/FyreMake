<?php
declare(strict_types=1);

namespace Fyre\Make\Commands;

use Fyre\Command\Command;
use Fyre\Console\Console;
use Fyre\Make\Make;
use Fyre\Utility\Path;
use Fyre\View\CellRegistry;

use function file_exists;

/**
 * MakeCellCommand
 */
class MakeCellCommand extends Command
{
    protected string|null $alias = 'make:cell';

    protected string $description = 'Generate a new cell.';

    protected array $options = [
        'name' => [
            'text' => 'Please enter a name for the cell',
            'required' => true,
        ],
        'method' => [
            'default' => 'display',
        ],
        'namespace' => [],
    ];

    /**
     * Run the command.
     *
     * @param Make $make The Make.
     * @param CellRegistry $cellRegistry The CellRegistry.
     * @param Console $io The Console.
     * @param string $name The cell name.
     * @param string $method The cell method.
     * @param string|null $namespace The cell namespace.
     * @return int|null The exit code.
     */
    public function run(Make $make, CellRegistry $cellRegistry, Console $io, string $name, string $method, string|null $namespace = null): int|null
    {
        $namespace ??= $cellRegistry->getNamespaces()[0] ?? 'App\Cells';

        [$namespace, $className] = Make::parseNamespaceClass($namespace, $name.'Cell');

        $path = $make->findPath($namespace);

        if (!$path) {
            $io->error('Namespace path not found.');

            return static::CODE_ERROR;
        }

        $fullPath = Path::join($path, $className.'.php');

        if (file_exists($fullPath)) {
            $io->error('Cell file already exists.');

            return static::CODE_ERROR;
        }

        $contents = Make::loadStub('cell', [
            '{namespace}' => $namespace,
            '{class}' => $className,
            '{method}' => $method,
        ]);

        if (!Make::saveFile($fullPath, $contents)) {
            $io->error('Cell file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
