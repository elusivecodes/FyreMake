<?php
declare(strict_types=1);

namespace Fyre\Make;

use Fyre\Console\Console;
use Fyre\Entity\EntityLocator;
use Fyre\Utility\Path;

use function file_exists;

/**
 * MakeEntityCommand
 */
class MakeEntityCommand extends MakeCommand
{
    protected string|null $alias = 'make:entity';

    protected string $description = 'This command will generate a new entity.';

    protected string|null $name = 'Make Entity';

    /**
     * Run the command.
     *
     * @param array $arguments The command arguments.
     * @return int|null The exit code.
     */
    public function run(array $arguments = []): int|null
    {
        $entity = $arguments[0] ?? null;
        $namespace = $arguments['namespace'] ?? EntityLocator::getNamespaces()[0] ?? 'App\Entities';

        if (!$entity) {
            $entity = Console::prompt('Enter a name for the entity');
        }

        if (!$entity) {
            Console::error('Invalid entity name.');

            return static::CODE_ERROR;
        }

        [$namespace, $className] = static::parseNamespaceClass($namespace, $entity);

        $path = static::findPath($namespace);

        if (!$path) {
            Console::error('Namespace path not found.');

            return static::CODE_ERROR;
        }

        $fullPath = Path::join($path, $className.'.php');

        if (file_exists($fullPath)) {
            Console::error('Entity file already exists.');

            return static::CODE_ERROR;
        }

        $contents = static::loadStub('entity', [
            '{namespace}' => $namespace,
            '{class}' => $className,
        ]);

        if (!static::saveFile($fullPath, $contents)) {
            Console::error('Entity file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
