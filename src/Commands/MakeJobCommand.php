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

    protected string $description = 'Generate a new job.';

    protected array $options = [
        'name' => [
            'text' => 'Please enter a name for the job',
            'required' => true,
        ],
        'namespace' => [],
    ];

    /**
     * Run the command.
     *
     * @param Make $make The Make.
     * @param Console $io The Console.
     * @param string $name The job name.
     * @param string|null $namespace The job namespace.
     * @return int|null The exit code.
     */
    public function run(Make $make, Console $io, string $name, string|null $namespace = null): int|null
    {
        $namespace ??= 'App\Jobs';

        [$namespace, $className] = Make::parseNamespaceClass($namespace, $name.'Job');

        $path = $make->findPath($namespace);

        if (!$path) {
            $io->error('Namespace path not found.');

            return static::CODE_ERROR;
        }

        $fullPath = Path::join($path, $className.'.php');

        if (file_exists($fullPath)) {
            $io->error('Job file already exists.');

            return static::CODE_ERROR;
        }

        $contents = Make::loadStub('job', [
            '{namespace}' => $namespace,
            '{class}' => $className,
        ]);

        if (!Make::saveFile($fullPath, $contents)) {
            $io->error('Job file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
