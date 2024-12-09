<?php
declare(strict_types=1);

namespace Fyre\Make\Commands;

use Fyre\Auth\PolicyRegistry;
use Fyre\Command\Command;
use Fyre\Console\Console;
use Fyre\Make\Make;
use Fyre\Utility\Path;

use function file_exists;

/**
 * MakePolicyCommand
 */
class MakePolicyCommand extends Command
{
    protected string|null $alias = 'make:policy';

    protected string $description = 'Generate a new policy.';

    protected array $options = [
        'name' => [
            'text' => 'Please enter a name for the policy',
            'required' => true,
        ],
        'namespace' => [],
    ];

    /**
     * Run the command.
     *
     * @param Make $make The Make.
     * @param PolicyRegistry $policyRegistry The PolicyRegistry.
     * @param Console $io The Console.
     * @param string $name The policy name.
     * @param string|null $namespace The policy namespace.
     * @return int|null The exit code.
     */
    public function run(Make $make, PolicyRegistry $policyRegistry, Console $io, string $name, string|null $namespace = null): int|null
    {
        $namespace ??= $policyRegistry->getNamespaces()[0] ?? 'App\Policies';

        [$namespace, $className] = Make::parseNamespaceClass($namespace, $name.'Policy');

        $path = $make->findPath($namespace);

        if (!$path) {
            $io->error('Namespace path not found.');

            return static::CODE_ERROR;
        }

        $fullPath = Path::join($path, $className.'.php');

        if (file_exists($fullPath)) {
            $io->error('Policy file already exists.');

            return static::CODE_ERROR;
        }

        $contents = Make::loadStub('policy', [
            '{namespace}' => $namespace,
            '{class}' => $className,
        ]);

        if (!Make::saveFile($fullPath, $contents)) {
            $io->error('Policy file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
