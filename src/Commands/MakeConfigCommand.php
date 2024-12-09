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

    protected string $description = 'Generate a new config file.';

    protected array $options = [
        'file' => [
            'text' => 'Please enter the config file',
            'required' => true,
        ],
        'path' => [],
    ];

    /**
     * Run the command.
     *
     * @param array $arguments The command arguments.
     * @return int|null The exit code.
     */
    public function run(Config $config, Console $io, string $file, string|null $path = null): int|null
    {
        $path ??= $config->getPaths()[0] ?? '';

        if (file_exists($path) && !is_dir($path)) {
            $io->error('Invalid config path.');

            return static::CODE_ERROR;
        }

        $file = Make::normalizePath($file);

        $fullPath = Path::join($path, $file.'.php');

        if (file_exists($fullPath)) {
            $io->error('Config file already exists.');

            return static::CODE_ERROR;
        }

        $contents = Make::loadStub('config');

        if (!Make::saveFile($fullPath, $contents)) {
            $io->error('Config file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
