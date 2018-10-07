<?php

/*
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace olvlvl\DoctrineGenerators\Document;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Proxy\ProxyFactory;

class ProxyGenerator
{
    use EnsureDirectory;

    /**
     * @return string[]
     */
    public function __invoke(DocumentManager $documentManager): array
    {
        $metadataCollection = $this->getMetadataCollection($documentManager);

        if (count($metadataCollection) === 0) {
            return [];
        }

        $this->getProxyFactory($documentManager)->generateProxyClasses(
            $metadataCollection,
            $this->ensureDirectory($this->getProxyDir($documentManager))
        );

        return array_map(function (ClassMetadata $metadata) {
            return $metadata->name;
        }, $metadataCollection);
    }

    private function getMetadataCollection(DocumentManager $documentManager): array
    {
        return $documentManager->getMetadataFactory()->getAllMetadata();
    }

    private function getProxyDir(DocumentManager $documentManager): string
    {
        return $documentManager->getConfiguration()->getProxyDir();
    }

    private function getProxyFactory(DocumentManager $documentManager): ProxyFactory
    {
        return $documentManager->getProxyFactory();
    }
}
