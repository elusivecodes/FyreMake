<?php
declare(strict_types=1);

namespace Fyre\Make\Commands;

use Fyre\Command\Command;
use Fyre\Console\Console;
use Fyre\Make\Make;
use Fyre\Utility\Path;

use function file_exists;

/**
 * MakeJobCommand
 */
class MakeJobCommand extends Command
{
    protected string|null $alias = 'make:job';

    protected string $description = 'This command will generate a new job.';

    protected string|null $name = 'Make Job';

    /**
     * Run the command.
     *
     * @param array $arguments The command arguments.
     * @return int|null The exit code.
     */
    public function run(array $arguments = []): int|null
    {
        $job = $arguments[0] ?? null;
        $namespace = $arguments['namespace'] ?? 'App\Jobs';

        if (!$job) {
            $job = Console::prompt('Enter a name for the job');
        }

        if (!$job) {
            Console::error('Invalid job name.');

            return static::CODE_ERROR;
        }

        [$namespace, $className] = Make::parseNamespaceClass($namespace, $job.'Job');

        $path = Make::findPath($namespace);

        if (!$path) {
            Console::error('Namespace path not found.');

            return static::CODE_ERROR;
        }

        $fullPath = Path::join($path, $className.'.php');

        if (file_exists($fullPath)) {
            Console::error('Job file already exists.');

            return static::CODE_ERROR;
        }

        $contents = Make::loadStub('job', [
            '{namespace}' => $namespace,
            '{class}' => $className,
        ]);

        if (!Make::saveFile($fullPath, $contents)) {
            Console::error('Job file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
