<?php

/*
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace olvlvl\DoctrineGenerators\Document;

use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataFactory;
use Doctrine\ODM\MongoDB\Mapping\Driver\SimplifiedYamlDriver;
use function getenv;
use function realpath;

class TestCase extends \PHPUnit\Framework\TestCase
{
    private $cacheDir;

    public function setUp()
    {
        $dir = realpath(__DIR__ . '/../sandbox');

        if (file_exists($dir)) {
            shell_exec("rm -rf $dir/*");
        }

        $this->cacheDir = $dir;

        parent::setUp();
    }

    protected function createDocumentManager(Configuration $config): DocumentManager
    {
        return DocumentManager::create(
            new Connection(getenv('MONGODB_SERVER'), [], $config),
            $config
        );
    }

    protected function createConfig(): Configuration
    {
        $database = "testing";
        $cacheDir = $this->cacheDir;

        $config = new Configuration();
        $config->setProxyDir("$cacheDir/Proxies");
        $config->setProxyNamespace('Proxies');
        $config->setAutoGenerateProxyClasses(Configuration::AUTOGENERATE_NEVER);
        $config->setHydratorDir("$cacheDir/Hydrators");
        $config->setHydratorNamespace('Hydrators');
        $config->setAutoGenerateHydratorClasses(Configuration::AUTOGENERATE_NEVER);
        $config->setDefaultDB($database);
        $config->setMetadataDriverImpl(
            new SimplifiedYamlDriver([ __DIR__ . '/dcm' => __NAMESPACE__ ], '.dcm.yml')
        );

        return $config;
    }

    protected function aDocumentManagerWithEmptyMetadata(): DocumentManager
    {
        $factory = $this->prophesize(ClassMetadataFactory::class);
        $factory->getAllMetadata()->willReturn([]);

        $documentManager = $this->prophesize(DocumentManager::class);
        $documentManager->getMetadataFactory()->willReturn($factory);

        return $documentManager->reveal();
    }
}
