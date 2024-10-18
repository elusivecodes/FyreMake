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

    protected string $description = 'This command will generate a new policy.';

    protected string|null $name = 'Make Policy';

    /**
     * Run the command.
     *
     * @param array $arguments The command arguments.
     * @return int|null The exit code.
     */
    public function run(array $arguments = []): int|null
    {
        $policy = $arguments[0] ?? null;
        $namespace = $arguments['namespace'] ?? PolicyRegistry::getNamespaces()[0] ?? 'App\Policies';

        if (!$policy) {
            $policy = Console::prompt('Enter a name for the policy');
        }

        if (!$policy) {
            Console::error('Invalid policy name.');

            return static::CODE_ERROR;
        }

        [$namespace, $className] = Make::parseNamespaceClass($namespace, $policy.'Policy');

        $path = Make::findPath($namespace);

        if (!$path) {
            Console::error('Namespace path not found.');

            return static::CODE_ERROR;
        }

        $fullPath = Path::join($path, $className.'.php');

        if (file_exists($fullPath)) {
            Console::error('Policy file already exists.');

            return static::CODE_ERROR;
        }

        $contents = Make::loadStub('policy', [
            '{namespace}' => $namespace,
            '{class}' => $className,
        ]);

        if (!Make::saveFile($fullPath, $contents)) {
            Console::error('Policy file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
