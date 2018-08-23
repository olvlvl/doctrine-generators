<?php

/*
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace olvlvl\DoctrineGenerators\Document;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataFactory;

/**
 * @group integration
 */
class HydratorGeneratorTest extends TestCase
{
    public function testGenerator()
    {
        $classes = (new HydratorGenerator(
            $this->createDocumentManager($config = $this->createConfig())
        ))();

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
        $generator = new HydratorGenerator(
            $this->aDocumentManagerWithEmptyMetadata()
        );

        $this->assertSame([], $generator());
    }
}
