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
 * MakeCellTemplateCommand
 */
class MakeCellTemplateCommand extends Command
{
    protected string|null $alias = 'make:cell_template';

    protected string $description = 'This command will generate a new cell template.';

    protected string|null $name = 'Make Cell Template';

    /**
     * Run the command.
     *
     * @param array $arguments The command arguments.
     * @return int|null The exit code.
     */
    public function run(array $arguments = []): int|null
    {
        $template = $arguments[0] ?? null;
        $path = $arguments['path'] ?? Template::getPaths()[0] ?? '';
        $cellsFolder = Template::CELLS_FOLDER;

        if (!$template) {
            $template = Console::prompt('Enter a name for the template');
        }

        if (!$template) {
            Console::error('Invalid template name.');

            return static::CODE_ERROR;
        }

        if (file_exists($path) && !is_dir($path)) {
            Console::error('Invalid template path.');

            return static::CODE_ERROR;
        }

        $template = Make::normalizePath($template);

        $fullPath = Path::join($path, $cellsFolder, $template.'.php');

        if (file_exists($fullPath)) {
            Console::error('Cell template file already exists.');

            return static::CODE_ERROR;
        }

        $contents = Make::loadStub('cell_template');

        if (!Make::saveFile($fullPath, $contents)) {
            Console::error('Cell template file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
