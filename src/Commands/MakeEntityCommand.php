<?php
declare(strict_types=1);

namespace Fyre\Make\Commands;

use Fyre\Command\Command;
use Fyre\Console\Console;
use Fyre\Entity\EntityLocator;
use Fyre\Make\Make;
use Fyre\Utility\Path;

use function file_exists;

/**
 * MakeEntityCommand
 */
class MakeEntityCommand extends Command
{
    protected string|null $alias = 'make:entity';

    protected string $description = 'Generate a new entity.';

    protected array $options = [
        'name' => [
            'text' => 'Please enter a name for the entity',
            'required' => true,
        ],
        'namespace' => [],
    ];

    /**
     * Run the command.
     *
     * @param Make $make The Make.
     * @param EntityLocator $entityLocator The EntityLocator.
     * @param Console $io The Console.
     * @param string $name The entity name.
     * @param string|null $namespace The entity namespace.
     * @return int|null The exit code.
     */
    public function run(Make $make, EntityLocator $entityLocator, Console $io, string $name, string|null $namespace = null): int|null
    {
        $namespace ??= $entityLocator->getNamespaces()[0] ?? 'App\Entities';

        [$namespace, $className] = Make::parseNamespaceClass($namespace, $name);

        $path = $make->findPath($namespace);

        if (!$path) {
            $io->error('Namespace path not found.');

            return static::CODE_ERROR;
        }

        $fullPath = Path::join($path, $className.'.php');

        if (file_exists($fullPath)) {
            $io->error('Entity file already exists.');

            return static::CODE_ERROR;
        }

        $contents = Make::loadStub('entity', [
            '{namespace}' => $namespace,
            '{class}' => $className,
        ]);

        if (!Make::saveFile($fullPath, $contents)) {
            $io->error('Entity file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
