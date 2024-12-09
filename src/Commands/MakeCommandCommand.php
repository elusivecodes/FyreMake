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

    protected string $description = 'Generate a new command.';

    protected array $options = [
        'name' => [
            'text' => 'Please enter a name for the command',
            'required' => true,
        ],
        'alias' => [],
        'description' => [],
        'namespace' => [],
    ];

    /**
     * Run the command.
     *
     * @param Make $make The Make.
     * @param CommandRunner $commandRunner The CommandRunner.
     * @param Console $io The Console.
     * @param string $name The command name.
     * @param string|null $alias The command alias.
     * @param string|null $description The command description.
     * @param string|null $namespace The command namespace.
     * @return int|null The exit code.
     */
    public function run(Make $make, CommandRunner $commandRunner, Console $io, string $name, string|null $alias = null, string|null $description = null, string|null $namespace = null): int|null
    {
        $namespace ??= $commandRunner->getNamespaces()[0] ?? 'App\Commands';

        [$namespace, $className] = Make::parseNamespaceClass($namespace, $name.'Command');

        $path = $make->findPath($namespace);

        if (!$path) {
            $io->error('Namespace path not found.');

            return static::CODE_ERROR;
        }

        $fullPath = Path::join($path, $className.'.php');

        if (file_exists($fullPath)) {
            $io->error('Command file already exists.');

            return static::CODE_ERROR;
        }

        $command = preg_replace('/Command$/', '', $className);
        $alias ??= strtolower(preg_replace('/(?<!^)([A-Z]+)/', '_$1', $command));

        $contents = Make::loadStub('command', [
            '{namespace}' => $namespace,
            '{class}' => $className,
            '{alias}' => $alias,
            '{description}' => $description ?? '',
        ]);

        if (!Make::saveFile($fullPath, $contents)) {
            $io->error('Command file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
