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
 * MakeElementCommand
 */
class MakeElementCommand extends Command
{
    protected string|null $alias = 'make:element';

    protected string $description = 'Generate a new element.';

    protected array $options = [
        'template' => [
            'text' => 'Please enter the element template',
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
            $io->error('Invalid element path.');

            return static::CODE_ERROR;
        }

        $template = Make::normalizePath($template);

        $fullPath = Path::join($path, TemplateLocator::ELEMENTS_FOLDER, $template.'.php');

        if (file_exists($fullPath)) {
            $io->error('Element file already exists.');

            return static::CODE_ERROR;
        }

        $contents = Make::loadStub('element');

        if (!Make::saveFile($fullPath, $contents)) {
            $io->error('Element file could not be written.');

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }
}
