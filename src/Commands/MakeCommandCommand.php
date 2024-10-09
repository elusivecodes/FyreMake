<?php
declare(strict_types=1);

namespace Fyre\Make\Commands;

use Fyre\Command\Command;
use Fyre\Command\CommandRunner;
use Fyre\Console\Console;
use Fyre\Make\Make;
use Fyre\Utility\Path;

use function file_exists;
use function preg_replace;
use function strtolower;

/**
 * MakeCommandCommand
 */
class MakeCommandCommand extends Command
{
    protected string|null $alias = 'make:command';

    protected string $description = 'This command will generate a new command.';

    protected string|null $name = 'Make Command';

    /**
     * Run the command.
     *
     * @param array $arguments The command arguments.
     * @return int|null The exit code.
     */
    public function run(array $arguments = []): int|null
    {
        $command = $arguments[0] ?? null;
        $alias = $arguments['alias'] ?? null;
        $name = $arguments['name'] ?? null;
        $description = $arguments['description'] ?? '';
        $namespace = $arguments['namespace'] ?? CommandRunner::getNamespaces()[0] ?? 'App\Commands';

        if (!$command) {
            $command = Console::prompt('Enter a name for the command');
        }

        if (!$command) {
            Console::error('Invalid command name.');

            return static::CODE_ERROR;
        }

        [$namespace, $className] = Make::parseNamespaceClass($namespace, $command.'Command');

        $path = Make::findPath($namespace);

        if (!$path) {
            Console::error('Namespace path not found.');

            return static::CODE_ERROR;
        }

        $fullPath = Path::join($path, $className.'.php');

        if (file_exists($fullPath)) {
            Console::error('Command file already exists.');

            return static::CODE_ERROR;
        }

        $command = preg_replace('/Command$/', '', $className);
        $alias ??= strtolower(preg_replace('/(?<!^)([A-Z]+)/', '_$1', $command));
        $name ??= preg_replace('/(?<!^)([A-Z]+)/', ' $1', $command);

        $contents = Make::loadStub('command', [
            '{namespace}' => $namespace,
            '{class}' => $className,
            '{alias}' => $alias,
            '{name}' => $name,
            '{description}' => $description,
        ]);

        if (!Make::saveFile($fullPath, $contents)) {
            Console::error('Command file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
