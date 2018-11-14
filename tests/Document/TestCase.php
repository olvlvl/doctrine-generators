<?php

/*
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace olvlvl\DoctrineGenerators\Document;

use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataFactory;
use MongoDB\Client;
use olvlvl\DoctrineYamlDriver\MongoDB\SimplifiedYamlDriver;
use function getenv;
use function realpath;

class TestCase extends \PHPUnit\Framework\TestCase
{
    use EnsureDirectory;

    private $cacheDir;

    public function setUp()
    {
        $dir = realpath(__DIR__ . '/../sandbox');

        if (!$dir) {
            throw new \RuntimeException("sandbox directory is missing.");
        }

        if (file_exists($dir)) {
            shell_exec("rm -rf $dir/*");
        }

        $this->cacheDir = $dir;

        parent::setUp();
    }

    protected function createDocumentManager(Configuration $config): DocumentManager
    {
        $client = new Client(
            'mongodb://'.getenv('MONGODB_SERVER'),
            [],
            [ 'typeMap' => DocumentManager::CLIENT_TYPEMAP ]
        );

        return DocumentManager::create($client, $config);
    }

    protected function createConfig(): Configuration
    {
        $database = "testing";
        $cacheDir = $this->cacheDir;

        $config = new Configuration();
        $config->setProxyDir($this->ensureDirectory("$cacheDir/Proxies"));
        $config->setProxyNamespace('Proxies');
        $config->setAutoGenerateProxyClasses(Configuration::AUTOGENERATE_FILE_NOT_EXISTS);
        $config->setHydratorDir($this->ensureDirectory("$cacheDir/Hydrators"));
        $config->setHydratorNamespace('Hydrators');
        $config->setAutoGenerateHydratorClasses(Configuration::AUTOGENERATE_FILE_NOT_EXISTS);
        $config->setDefaultDB($database);
        $config->setMetadataDriverImpl(
            new SimplifiedYamlDriver([ __DIR__ . '/dcm' => __NAMESPACE__ ])
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
