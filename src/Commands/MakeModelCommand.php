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

    protected string $description = 'This command will generate a new model.';

    protected string|null $name = 'Make Model';

    /**
     * Run the command.
     *
     * @param array $arguments The command arguments.
     * @return int|null The exit code.
     */
    public function run(array $arguments = []): int|null
    {
        $model = $arguments[0] ?? null;
        $namespace = $arguments['namespace'] ?? ModelRegistry::getNamespaces()[0] ?? 'App\Model';

        if (!$model) {
            $model = Console::prompt('Enter a name for the model');
        }

        if (!$model) {
            Console::error('Invalid model name.');

            return static::CODE_ERROR;
        }

        [$namespace, $className] = Make::parseNamespaceClass($namespace, $model.'Model');

        $path = Make::findPath($namespace);

        if (!$path) {
            Console::error('Namespace path not found.');

            return static::CODE_ERROR;
        }

        $fullPath = Path::join($path, $className.'.php');

        if (file_exists($fullPath)) {
            Console::error('Model file already exists.');

            return static::CODE_ERROR;
        }

        $contents = Make::loadStub('model', [
            '{namespace}' => $namespace,
            '{class}' => $className,
        ]);

        if (!Make::saveFile($fullPath, $contents)) {
            Console::error('Model file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
