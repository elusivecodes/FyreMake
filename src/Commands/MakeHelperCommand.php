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

    protected string $description = 'Generate a new helper.';

    protected array $options = [
        'name' => [
            'text' => 'Please enter a name for the helper',
            'required' => true,
        ],
        'namespace' => [],
    ];

    /**
     * Run the command.
     *
     * @param Make $make The Make.
     * @param HelperRegistry $helperRegistry The HelperRegistry.
     * @param Console $io The Console.
     * @param string $name The helper name.
     * @param string|null $namespace The helper namespace.
     * @return int|null The exit code.
     */
    public function run(Make $make, HelperRegistry $helperRegistry, Console $io, string $name, string|null $namespace = null): int|null
    {
        $namespace ??= $helperRegistry->getNamespaces()[0] ?? 'App\Helpers';

        [$namespace, $className] = Make::parseNamespaceClass($namespace, $name.'Helper');

        $path = $make->findPath($namespace);

        if (!$path) {
            $io->error('Namespace path not found.');

            return static::CODE_ERROR;
        }

        $fullPath = Path::join($path, $className.'.php');

        if (file_exists($fullPath)) {
            $io->error('Helper file already exists.');

            return static::CODE_ERROR;
        }

        $contents = Make::loadStub('helper', [
            '{namespace}' => $namespace,
            '{class}' => $className,
        ]);

        if (!Make::saveFile($fullPath, $contents)) {
            $io->error('Helper file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
