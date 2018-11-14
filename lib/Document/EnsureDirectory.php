<?php

/*
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace olvlvl\DoctrineGenerators\Document;

trait EnsureDirectory
{
    private function ensureDirectory(string $dir): string
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0705, true);
        }

        $realpath = realpath($dir);

        // @codeCoverageIgnoreStart
        if (!file_exists($realpath)) {
            throw new \InvalidArgumentException(
                "Destination directory `$realpath` does not exist."
            );
        } elseif (!is_writable($realpath)) {
            throw new \InvalidArgumentException(
                "Destination directory `$realpath` does not have write permissions."
            );
        }
        // @codeCoverageIgnoreEnd

        return $realpath;
    }
}
