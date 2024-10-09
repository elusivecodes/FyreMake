<?php
declare(strict_types=1);

namespace Fyre\Make;

use Fyre\Console\Console;
use Fyre\ORM\BehaviorRegistry;
use Fyre\Utility\Path;

use function file_exists;

/**
 * MakeBehaviorCommand
 */
class MakeBehaviorCommand extends MakeCommand
{
    protected string|null $alias = 'make:behavior';

    protected string $description = 'This command will generate a new behavior.';

    protected string|null $name = 'Make Behavior';

    /**
     * Run the command.
     *
     * @param array $arguments The command arguments.
     * @return int|null The exit code.
     */
    public function run(array $arguments = []): int|null
    {
        $behavior = $arguments[0] ?? null;
        $namespace = $arguments['namespace'] ?? BehaviorRegistry::getNamespaces()[0] ?? 'App\Models\Behaviors';

        if (!$behavior) {
            $behavior = Console::prompt('Enter a name for the behavior');
        }

        if (!$behavior) {
            Console::error('Invalid behavior name.');

            return static::CODE_ERROR;
        }

        [$namespace, $className] = static::parseNamespaceClass($namespace, $behavior.'Behavior');

        $path = static::findPath($namespace);

        if (!$path) {
            Console::error('Namespace path not found.');

            return static::CODE_ERROR;
        }

        $fullPath = Path::join($path, $className.'.php');

        if (file_exists($fullPath)) {
            Console::error('Behavior file already exists.');

            return static::CODE_ERROR;
        }

        $contents = static::loadStub('behavior', [
            '{namespace}' => $namespace,
            '{class}' => $className,
        ]);

        if (!static::saveFile($fullPath, $contents)) {
            Console::error('Behavior file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
