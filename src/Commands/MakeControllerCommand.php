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

    protected string $description = 'Generate a new controller.';

    protected array $options = [
        'name' => [
            'text' => 'Please enter a name for the controller',
            'required' => true,
        ],
        'namespace' => [],
    ];

    /**
     * Run the command.
     *
     * @param Make $make The Make.
     * @param Console $io The Console.
     * @param string $name The controller name.
     * @param string|null $namespace The controller namespace.
     * @return int|null The exit code.
     */
    public function run(Make $make, Console $io, string $name, string|null $namespace = null): int|null
    {
        $namespace ??= 'App\Controllers';

        [$namespace, $className] = Make::parseNamespaceClass($namespace, $name.'Controller');

        $path = $make->findPath($namespace);

        if (!$path) {
            $io->error('Namespace path not found.');

            return static::CODE_ERROR;
        }

        $fullPath = Path::join($path, $className.'.php');

        if (file_exists($fullPath)) {
            $io->error('Controller file already exists.');

            return static::CODE_ERROR;
        }

        $contents = Make::loadStub('controller', [
            '{namespace}' => $namespace,
            '{class}' => $className,
        ]);

        if (!Make::saveFile($fullPath, $contents)) {
            $io->error('Controller file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
