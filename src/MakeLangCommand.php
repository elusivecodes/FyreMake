<?php
declare(strict_types=1);

namespace Fyre\Make;

use Fyre\Console\Console;
use Fyre\Lang\Lang;
use Fyre\Utility\Path;

use function file_exists;
use function is_dir;

/**
 * MakeLangCommand
 */
class MakeLangCommand extends MakeCommand
{
    protected string|null $alias = 'make:lang';

    protected string $description = 'This command will generate a new langage file.';

    protected string|null $name = 'Make Lang';

    /**
     * Run the command.
     *
     * @param array $arguments The command arguments.
     * @return int|null The exit code.
     */
    public function run(array $arguments = []): int|null
    {
        $lang = $arguments[0] ?? null;
        $language = $arguments['language'] ?? Lang::getDefaultLocale();
        $path = $arguments['path'] ?? Lang::getPaths()[0] ?? '';

        if (!$lang) {
            $lang = Console::prompt('Enter a name for the lang');
        }

        if (!$lang) {
            Console::error('Invalid lang name.');

            return static::CODE_ERROR;
        }

        if (file_exists($path) && !is_dir($path)) {
            Console::error('Invalid lang path.');

            return static::CODE_ERROR;
        }

        $lang = static::normalizePath($lang);

        $fullPath = Path::join($path, $language, $lang.'.php');

        if (file_exists($fullPath)) {
            Console::error('Lang file already exists.');

            return static::CODE_ERROR;
        }

        $contents = static::loadStub('lang');

        if (!static::saveFile($fullPath, $contents)) {
            Console::error('Lang file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
