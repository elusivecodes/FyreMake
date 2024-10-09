<?php
declare(strict_types=1);

namespace Fyre\Make\Commands;

use Fyre\Command\Command;
use Fyre\Console\Console;
use Fyre\Make\Make;
use Fyre\Utility\Path;
use Fyre\View\HelperRegistry;

use function file_exists;

/**
 * MakeHelperCommand
 */
class MakeHelperCommand extends Command
{
    protected string|null $alias = 'make:helper';

    protected string $description = 'This command will generate a new helper.';

    protected string|null $name = 'Make Helper';

    /**
     * Run the command.
     *
     * @param array $arguments The command arguments.
     * @return int|null The exit code.
     */
    public function run(array $arguments = []): int|null
    {
        $helper = $arguments[0] ?? null;
        $namespace = $arguments['namespace'] ?? HelperRegistry::getNamespaces()[0] ?? 'App\Helpers';

        if (!$helper) {
            $helper = Console::prompt('Enter a name for the helper');
        }

        if (!$helper) {
            Console::error('Invalid helper name.');

            return static::CODE_ERROR;
        }

        [$namespace, $className] = Make::parseNamespaceClass($namespace, $helper.'Helper');

        $path = Make::findPath($namespace);

        if (!$path) {
            Console::error('Namespace path not found.');

            return static::CODE_ERROR;
        }

        $fullPath = Path::join($path, $className.'.php');

        if (file_exists($fullPath)) {
            Console::error('Helper file already exists.');

            return static::CODE_ERROR;
        }

        $contents = Make::loadStub('helper', [
            '{namespace}' => $namespace,
            '{class}' => $className,
        ]);

        if (!Make::saveFile($fullPath, $contents)) {
            Console::error('Helper file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
