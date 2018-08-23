<?php

/*
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace olvlvl\DoctrineGenerators\Document;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Proxy\ProxyFactory;

class ProxyGenerator
{
    use EnsureDirectory;

    /**
     * @var DocumentManager
     */
    private $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    /**
     * @return string[]
     */
    public function __invoke(): array
    {
        $metadataCollection = $this->getMetadataCollection();

        if (count($metadataCollection) === 0) {
            return [];
        }

        $this->getProxyFactory()->generateProxyClasses(
            $metadataCollection,
            $this->ensureDirectory($this->getProxyDir())
        );

        return array_map(function ($metadata) {
            return $metadata->name;
        }, $metadataCollection);
    }

    private function getMetadataCollection(): array
    {
        return $this->documentManager->getMetadataFactory()->getAllMetadata();
    }

    private function getProxyDir(): string
    {
        return $this->documentManager->getConfiguration()->getProxyDir();
    }

    private function getProxyFactory(): ProxyFactory
    {
        return $this->documentManager->getProxyFactory();
    }
}
