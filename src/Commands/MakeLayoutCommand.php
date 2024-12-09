<?php
declare(strict_types=1);

namespace Fyre\Make\Commands;

use Fyre\Command\Command;
use Fyre\Console\Console;
use Fyre\Make\Make;
use Fyre\Utility\Path;
use Fyre\View\TemplateLocator;

use function file_exists;
use function is_dir;

/**
 * MakeLayoutCommand
 */
class MakeLayoutCommand extends Command
{
    protected string|null $alias = 'make:layout';

    protected string $description = 'Generate a new layout.';

    protected string|null $name = 'Make Layout';

    protected array $options = [
        'template' => [
            'text' => 'Please enter the layout template',
            'required' => true,
        ],
        'path' => [],
    ];

    /**
     * Run the command.
     *
     * @param TemplateLocator $templateLocator The TemplateLocator.
     * @param Console $io The Console.
     * @param string $template The template name.
     * @param string|null $path The template path.
     * @return int|null The exit code.
     */
    public function run(TemplateLocator $templateLocator, Console $io, string $template, string|null $path = null): int|null
    {
        $path ??= $templateLocator->getPaths()[0] ?? '';

        if (file_exists($path) && !is_dir($path)) {
            $io->error('Invalid layout path.');

            return static::CODE_ERROR;
        }

        $template = Make::normalizePath($template);

        $fullPath = Path::join($path, TemplateLocator::LAYOUTS_FOLDER, $template.'.php');

        if (file_exists($fullPath)) {
            $io->error('Layout file already exists.');

            return static::CODE_ERROR;
        }

        $contents = Make::loadStub('layout');

        if (!Make::saveFile($fullPath, $contents)) {
            $io->error('Layout file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
