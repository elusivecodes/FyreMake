<?php
declare(strict_types=1);

namespace Fyre\Make\Commands;

use Fyre\Command\Command;
use Fyre\Console\Console;
use Fyre\Make\Make;
use Fyre\Utility\Path;

use function file_exists;

/**
 * MakeMiddlewareCommand
 */
class MakeMiddlewareCommand extends Command
{
    protected string|null $alias = 'make:middleware';

    protected string $description = 'This command will generate a new middleware.';

    protected string|null $name = 'Make Middleware';

    /**
     * Run the command.
     *
     * @param array $arguments The command arguments.
     * @return int|null The exit code.
     */
    public function run(array $arguments = []): int|null
    {
        $middleware = $arguments[0] ?? null;
        $namespace = $arguments['namespace'] ?? 'App\Middleware';

        if (!$middleware) {
            $middleware = Console::prompt('Enter a name for the middleware');
        }

        if (!$middleware) {
            Console::error('Invalid middleware name.');

            return static::CODE_ERROR;
        }

        [$namespace, $className] = Make::parseNamespaceClass($namespace, $middleware.'Middleware');

        $path = Make::findPath($namespace);

        if (!$path) {
            Console::error('Namespace path not found.');

            return static::CODE_ERROR;
        }

        $fullPath = Path::join($path, $className.'.php');

        if (file_exists($fullPath)) {
            Console::error('Middleware file already exists.');

            return static::CODE_ERROR;
        }

        $contents = Make::loadStub('middleware', [
            '{namespace}' => $namespace,
            '{class}' => $className,
        ]);

        if (!Make::saveFile($fullPath, $contents)) {
            Console::error('Middleware file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
