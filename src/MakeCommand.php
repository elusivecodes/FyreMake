<?php
declare(strict_types=1);

namespace Fyre\Make;

use Fyre\Command\Command;
use Fyre\Loader\Loader;
use Fyre\Utility\Path;

use function array_keys;
use function array_pop;
use function array_unshift;
use function array_values;
use function explode;
use function file_get_contents;
use function file_put_contents;
use function implode;
use function ltrim;
use function preg_replace;
use function str_replace;
use function trim;

use const LOCK_EX;
use const PHP_EOL;

/**
 * MakeCommand
 */
abstract class MakeCommand extends Command
{
    /**
     * Load a stub file with replacements.
     *
     * @param string $stub The stub file.
     * @param array $replacements The replacements.
     * @return string The loaded stub file.
     */
    public static function loadStub(string $stub, array $replacements = []): string
    {
        $stubPath = Path::join(__DIR__, 'stubs', $stub.'.stub');

        $contents = file_get_contents($stubPath);
        $contents = preg_replace('/\R/u', PHP_EOL, $contents);

        return str_replace(array_keys($replacements), array_values($replacements), $contents);
    }

    /**
     * Find full path to a namespace.
     *
     * @param string $namespace The namespace.
     * @return string|null The full path.
     */
    protected static function findPath(string $namespace): string|null
    {
        $namespaceSegments = explode('\\', $namespace);
        $pathSegments = [];

        while ($namespaceSegments !== []) {
            $tempNamespace = implode('\\', $namespaceSegments);
            $paths = Loader::getNamespace($tempNamespace);

            if ($paths === []) {
                $segment = array_pop($namespaceSegments);
                array_unshift($pathSegments, $segment);

                continue;
            }

            return Path::join($paths[0], ...$pathSegments);
        }

        return null;
    }

    /**
     * Normalize a class name
     *
     * @param string $className The class name.
     * @return string The normalized class name.
     */
    protected static function normalizeClass(string $className): string
    {
        return ltrim($className, '\\');
    }

    /**
     * Normalize a namespace
     *
     * @param string $namespace The namespace.
     * @return string The normalized namespace.
     */
    protected static function normalizeNamespace(string $namespace): string
    {
        return trim($namespace, '\\').'\\';
    }

    /**
     * Normalize file path separators.
     *
     * @param string $string The string.
     * @return string The normalized string.
     */
    protected static function normalizePath(string $string): string
    {
        return str_replace('.', '/', $string);
    }

    /**
     * Normalize namespace path separators.
     *
     * @param string $string The string.
     * @return string The normalized string.
     */
    protected static function normalizeSeparators(string $string): string
    {
        return str_replace(['/', '.'], '\\', $string);
    }

    /**
     * Parse namespace and class name.
     *
     * @param string $namespace The namespace.
     * @param string $className The class name.
     * @return array The parsed namespace and class name.
     */
    protected static function parseNamespaceClass(string $namespace, string $className): array
    {
        $namespace = static::normalizeSeparators($namespace);
        $namespace = static::normalizeNamespace($namespace);

        $className = static::normalizeSeparators($className);
        $className = static::normalizeClass($className);

        $namespacedClass = $namespace.$className;

        $namespaceSegments = explode('\\', $namespacedClass);

        $className = array_pop($namespaceSegments);
        $namespace = implode('\\', $namespaceSegments);

        return [$namespace, $className];
    }

    /**
     * Save a new file.
     *
     * @param string $fullPath The file path.
     * @param string $contents The file contents.
     * @return bool TRUE if the file was written, otherwise FALSE.
     */
    protected static function saveFile(string $fullPath, string $contents): bool
    {
        $path = dirname($fullPath);

        if (!is_dir($path) && !mkdir($path, 0755, true)) {
            return false;
        }

        return file_put_contents($fullPath, $contents, LOCK_EX) !== false;
    }
}
