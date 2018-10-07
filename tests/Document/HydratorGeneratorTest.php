<?php

/*
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace olvlvl\DoctrineGenerators\Document;

/**
 * @group integration
 */
class HydratorGeneratorTest extends TestCase
{
    public function testGenerator()
    {
        $classes = (new HydratorGenerator)(
            $this->createDocumentManager($config = $this->createConfig())
        );

        $this->assertInternalType('array', $classes);

        $dir = $config->getHydratorDir();

        foreach ($classes as $class) {
            $filename = str_replace('\\', '', $class) . 'Hydrator.php';
            $this->assertFileExists("$dir/$filename");
        }
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArrayWhenThereIsNoMetadata()
    {
        $this->assertSame([], (new HydratorGenerator)($this->aDocumentManagerWithEmptyMetadata()));
    }
}
