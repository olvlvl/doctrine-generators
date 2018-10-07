<?php

/*
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace olvlvl\DoctrineGenerators\Document;

use Doctrine\Common\Proxy\Proxy;

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

        foreach ($classes as $class) {
            $filename = Proxy::MARKER . str_replace('\\', '', $class) . '.php';
            $this->assertFileExists("$dir/$filename");
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
