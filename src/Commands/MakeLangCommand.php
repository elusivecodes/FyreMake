<?php
declare(strict_types=1);

namespace Fyre\Make\Commands;

use Fyre\Command\Command;
use Fyre\Console\Console;
use Fyre\Lang\Lang;
use Fyre\Make\Make;
use Fyre\Utility\Path;

use function file_exists;
use function is_dir;

/**
 * MakeLangCommand
 */
class MakeLangCommand extends Command
{
    protected string|null $alias = 'make:lang';

    protected string $description = 'Generate a new langage file.';

    protected array $options = [
        'file' => [
            'text' => 'Please enter the languge file',
            'required' => true,
        ],
        'language' => [],
        'path' => [],
    ];

    /**
     * Run the command.
     *
     * @param array $arguments The command arguments.
     * @return int|null The exit code.
     */
    public function run(Lang $lang, Console $io, string $file, string|null $language = null, string|null $path = null): int|null
    {
        $language ??= $lang->getDefaultLocale();
        $path ??= $lang->getPaths()[0] ?? '';

        if (file_exists($path) && !is_dir($path)) {
            $io->error('Invalid lang path.');

            return static::CODE_ERROR;
        }

        $file = Make::normalizePath($file);

        $fullPath = Path::join($path, $language, $file.'.php');

        if (file_exists($fullPath)) {
            $io->error('Lang file already exists.');

            return static::CODE_ERROR;
        }

        $contents = Make::loadStub('lang');

        if (!Make::saveFile($fullPath, $contents)) {
            $io->error('Lang file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
