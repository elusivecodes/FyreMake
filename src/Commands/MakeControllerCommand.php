<?php
declare(strict_types=1);

namespace Fyre\Make\Commands;

use Fyre\Command\Command;
use Fyre\Console\Console;
use Fyre\Make\Make;
use Fyre\Utility\Path;

use function file_exists;

/**
 * MakeControllerCommand
 */
class MakeControllerCommand extends Command
{
    protected string|null $alias = 'make:controller';

    protected string $description = 'This command will generate a new controller.';

    protected string|null $name = 'Make Controller';

    /**
     * Run the command.
     *
     * @param array $arguments The command arguments.
     * @return int|null The exit code.
     */
    public function run(array $arguments = []): int|null
    {
        $controller = $arguments[0] ?? null;
        $namespace = $arguments['namespace'] ?? 'App\Controllers';

        if (!$controller) {
            $controller = Console::prompt('Enter a name for the controller');
        }

        if (!$controller) {
            Console::error('Invalid controller name.');

            return static::CODE_ERROR;
        }

        [$namespace, $className] = Make::parseNamespaceClass($namespace, $controller.'Controller');

        $path = Make::findPath($namespace);

        if (!$path) {
            Console::error('Namespace path not found.');

            return static::CODE_ERROR;
        }

        $fullPath = Path::join($path, $className.'.php');

        if (file_exists($fullPath)) {
            Console::error('Controller file already exists.');

            return static::CODE_ERROR;
        }

        $contents = Make::loadStub('controller', [
            '{namespace}' => $namespace,
            '{class}' => $className,
        ]);

        if (!Make::saveFile($fullPath, $contents)) {
            Console::error('Controller file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
