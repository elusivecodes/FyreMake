<?php
declare(strict_types=1);

namespace Fyre\Make;

use Fyre\Console\Console;
use Fyre\Utility\Path;
use Fyre\View\CellRegistry;

use function file_exists;

/**
 * MakeCellCommand
 */
class MakeCellCommand extends MakeCommand
{
    protected string|null $alias = 'make:cell';

    protected string $description = 'This command will generate a new cell.';

    protected string|null $name = 'Make Cell';

    /**
     * Run the command.
     *
     * @param array $arguments The command arguments.
     * @return int|null The exit code.
     */
    public function run(array $arguments = []): int|null
    {
        $cell = $arguments[0] ?? null;
        $method = $arguments['method'] ?? 'display';
        $namespace = $arguments['namespace'] ?? CellRegistry::getNamespaces()[0] ?? 'App\Cells';

        if (!$cell) {
            $cell = Console::prompt('Enter a name for the cell');
        }

        if (!$cell) {
            Console::error('Invalid cell name.');

            return static::CODE_ERROR;
        }

        [$namespace, $className] = static::parseNamespaceClass($namespace, $cell.'Cell');

        $path = static::findPath($namespace);

        if (!$path) {
            Console::error('Namespace path not found.');

            return static::CODE_ERROR;
        }

        $fullPath = Path::join($path, $className.'.php');

        if (file_exists($fullPath)) {
            Console::error('Cell file already exists.');

            return static::CODE_ERROR;
        }

        $contents = static::loadStub('cell', [
            '{namespace}' => $namespace,
            '{class}' => $className,
            '{method}' => $method,
        ]);

        if (!static::saveFile($fullPath, $contents)) {
            Console::error('Cell file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
