<?php
declare(strict_types=1);

namespace Fyre\Make;

use Fyre\Console\Console;
use Fyre\Utility\Path;
use Fyre\View\Template;

use function file_exists;
use function is_dir;

/**
 * MakeElementCommand
 */
class MakeElementCommand extends MakeCommand
{
    protected string|null $alias = 'make:element';

    protected string $description = 'This command will generate a new element.';

    protected string|null $name = 'Make Element';

    /**
     * Run the command.
     *
     * @param array $arguments The command arguments.
     * @return int|null The exit code.
     */
    public function run(array $arguments = []): int|null
    {
        $element = $arguments[0] ?? null;
        $path = $arguments['path'] ?? Template::getPaths()[0] ?? '';
        $elementsFolder = Template::ELEMENTS_FOLDER;

        if (!$element) {
            $element = Console::prompt('Enter a name for the element');
        }

        if (!$element) {
            Console::error('Invalid element name.');

            return static::CODE_ERROR;
        }

        if (file_exists($path) && !is_dir($path)) {
            Console::error('Invalid element path.');

            return static::CODE_ERROR;
        }

        $element = static::normalizePath($element);

        $fullPath = Path::join($path, $elementsFolder, $element.'.php');

        if (file_exists($fullPath)) {
            Console::error('Element file already exists.');

            return static::CODE_ERROR;
        }

        $contents = static::loadStub('element');

        if (!static::saveFile($fullPath, $contents)) {
            Console::error('Element file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
