<?php

/*
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace olvlvl\DoctrineGenerators\Document;

use ProxyManager\FileLocator\FileLocator;
use function glob;
use function preg_replace;

/**
 * @group integration
 */
class ProxyGeneratorTest extends TestCase
{
    public function testGenerator()
    {
        $classes = (new ProxyGenerator)(
            $this->createDocumentManager($config = $this->createConfig())
        );

        $this->assertInternalType('array', $classes);

        $dir = $config->getProxyDir();
        $locator = new FileLocator($dir);

        foreach ($classes as $class) {
            $proxyClass = $config
                ->getProxyManagerConfiguration()
                ->getClassNameInflector()
                ->getProxyClassName($class, []);
            $proxyClass = $locator->getProxyFileName($proxyClass);
            $proxyClass = preg_replace('/Generated.+/', '', $proxyClass);

            $this->assertCount(1, glob($proxyClass.'*'));
        }
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArrayWhenThereIsNoMetadata()
    {
        $this->assertSame([], (new ProxyGenerator)($this->aDocumentManagerWithEmptyMetadata()));
    }
}
