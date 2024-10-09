<?php
declare(strict_types=1);

namespace Fyre\Make\Commands;

use Fyre\Command\Command;
use Fyre\Config\Config;
use Fyre\Console\Console;
use Fyre\Make\Make;
use Fyre\Utility\Path;

use function file_exists;
use function is_dir;

/**
 * MakeConfigCommand
 */
class MakeConfigCommand extends Command
{
    protected string|null $alias = 'make:config';

    protected string $description = 'This command will generate a new config file.';

    protected string|null $name = 'Make Config';

    /**
     * Run the command.
     *
     * @param array $arguments The command arguments.
     * @return int|null The exit code.
     */
    public function run(array $arguments = []): int|null
    {
        $config = $arguments[0] ?? null;
        $path = $arguments['path'] ?? Config::getPaths()[0] ?? '';

        if (!$config) {
            $config = Console::prompt('Enter a name for the config');
        }

        if (!$config) {
            Console::error('Invalid config name.');

            return static::CODE_ERROR;
        }

        if (file_exists($path) && !is_dir($path)) {
            Console::error('Invalid config path.');

            return static::CODE_ERROR;
        }

        $config = Make::normalizePath($config);

        $fullPath = Path::join($path, $config.'.php');

        if (file_exists($fullPath)) {
            Console::error('Config file already exists.');

            return static::CODE_ERROR;
        }

        $contents = Make::loadStub('config');

        if (!Make::saveFile($fullPath, $contents)) {
            Console::error('Config file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
