<?php
declare(strict_types=1);

namespace Fyre\Make\Commands;

use Fyre\Command\Command;
use Fyre\Console\Console;
use Fyre\Make\Make;
use Fyre\ORM\BehaviorRegistry;
use Fyre\Utility\Path;

use function file_exists;

/**
 * MakeBehaviorCommand
 */
class MakeBehaviorCommand extends Command
{
    protected string|null $alias = 'make:behavior';

    protected string $description = 'Generate a new behavior.';

    protected array $options = [
        'name' => [
            'text' => 'Please enter a name for the behavior',
            'required' => true,
        ],
        'namespace' => [],
    ];

    /**
     * Run the command.
     *
     * @param Make $make The Make.
     * @param BehaviorRegistry $behaviorRegistry The BehaviorRegistry.
     * @param Console $io The Console.
     * @param string $name The behavior name.
     * @param string|null $namespace The behavior namespace.
     * @return int|null The exit code.
     */
    public function run(Make $make, BehaviorRegistry $behaviorRegistry, Console $io, string $name, string|null $namespace = null): int|null
    {
        $namespace ??= $behaviorRegistry->getNamespaces()[0] ?? 'App\Models\Behaviors';

        [$namespace, $className] = Make::parseNamespaceClass($namespace, $name.'Behavior');

        $path = $make->findPath($namespace);

        if (!$path) {
            $io->error('Namespace path not found.');

            return static::CODE_ERROR;
        }

        $fullPath = Path::join($path, $className.'.php');

        if (file_exists($fullPath)) {
            $io->error('Behavior file already exists.');

            return static::CODE_ERROR;
        }

        $contents = Make::loadStub('behavior', [
            '{namespace}' => $namespace,
            '{class}' => $className,
        ]);

        if (!Make::saveFile($fullPath, $contents)) {
            $io->error('Behavior file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
