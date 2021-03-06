<?php

/*
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace olvlvl\DoctrineGenerators\Document;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Hydrator\HydratorFactory;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

class HydratorGenerator
{
    /**
     * @return string[]
     */
    public function __invoke(DocumentManager $documentManager): array
    {
        $metadataCollection = $this->getMetadataCollection($documentManager);

        if (count($metadataCollection) === 0) {
            return [];
        }

        $this->getHydratorFactory($documentManager)->generateHydratorClasses(
            $metadataCollection,
            $this->getHydratorDir($documentManager)
        );

        return array_map(function (ClassMetadata $metadata) {
            return $metadata->name;
        }, $metadataCollection);
    }

    private function getMetadataCollection(DocumentManager $documentManager): array
    {
        return $documentManager->getMetadataFactory()->getAllMetadata();
    }

    private function getHydratorDir(DocumentManager $documentManager): string
    {
        return $documentManager->getConfiguration()->getHydratorDir();
    }

    private function getHydratorFactory(DocumentManager $documentManager): HydratorFactory
    {
        return $documentManager->getHydratorFactory();
    }
}
