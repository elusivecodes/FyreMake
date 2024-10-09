<?php
declare(strict_types=1);

namespace Fyre\Make\Commands;

use Fyre\Command\Command;
use Fyre\Console\Console;
use Fyre\Make\Make;
use Fyre\Utility\Path;
use Fyre\View\Template;

use function file_exists;
use function is_dir;

/**
 * MakeLayoutCommand
 */
class MakeLayoutCommand extends Command
{
    protected string|null $alias = 'make:layout';

    protected string $description = 'This command will generate a new layout.';

    protected string|null $name = 'Make Layout';

    /**
     * Run the command.
     *
     * @param array $arguments The command arguments.
     * @return int|null The exit code.
     */
    public function run(array $arguments = []): int|null
    {
        $layout = $arguments[0] ?? null;
        $path = $arguments['path'] ?? Template::getPaths()[0] ?? '';
        $layoutsFolder = Template::LAYOUTS_FOLDER;

        if (!$layout) {
            $layout = Console::prompt('Enter a name for the layout');
        }

        if (!$layout) {
            Console::error('Invalid layout name.');

            return static::CODE_ERROR;
        }

        if (file_exists($path) && !is_dir($path)) {
            Console::error('Invalid layout path.');

            return static::CODE_ERROR;
        }

        $layout = Make::normalizePath($layout);

        $fullPath = Path::join($path, $layoutsFolder, $layout.'.php');

        if (file_exists($fullPath)) {
            Console::error('Layout file already exists.');

            return static::CODE_ERROR;
        }

        $contents = Make::loadStub('layout');

        if (!Make::saveFile($fullPath, $contents)) {
            Console::error('Layout file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
