<?php
declare(strict_types=1);

namespace Fyre\Make\Commands;

use Fyre\Command\Command;
use Fyre\Console\Console;
use Fyre\Make\Make;
use Fyre\ORM\ModelRegistry;
use Fyre\Utility\Path;

use function file_exists;

/**
 * MakeModelCommand
 */
class MakeModelCommand extends Command
{
    protected string|null $alias = 'make:model';

    protected string $description = 'Generate a new model.';

    protected array $options = [
        'name' => [
            'text' => 'Please enter a name for the model',
            'required' => true,
        ],
        'namespace' => [],
    ];

    /**
     * Run the command.
     *
     * @param Make $make The Make.
     * @param ModelRegistry $modelRegistry The ModelRegistry.
     * @param Console $io The Console.
     * @param string $name The model name.
     * @param string|null $namespace The model namespace.
     * @return int|null The exit code.
     */
    public function run(Make $make, ModelRegistry $modelRegistry, Console $io, string $name, string|null $namespace = null): int|null
    {
        $namespace ??= $modelRegistry->getNamespaces()[0] ?? 'App\Models';

        [$namespace, $className] = Make::parseNamespaceClass($namespace, $name.'Model');

        $path = $make->findPath($namespace);

        if (!$path) {
            $io->error('Namespace path not found.');

            return static::CODE_ERROR;
        }

        $fullPath = Path::join($path, $className.'.php');

        if (file_exists($fullPath)) {
            $io->error('Model file already exists.');

            return static::CODE_ERROR;
        }

        $contents = Make::loadStub('model', [
            '{namespace}' => $namespace,
            '{class}' => $className,
        ]);

        if (!Make::saveFile($fullPath, $contents)) {
            $io->error('Model file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
