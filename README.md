# Doctrine Generators

[![Packagist](https://img.shields.io/packagist/v/olvlvl/doctrine-generators.svg)](https://packagist.org/packages/olvlvl/doctrine-generators)
[![Build Status](https://img.shields.io/travis/olvlvl/doctrine-generators.svg)](http://travis-ci.org/olvlvl/doctrine-generators)
[![Code Quality](https://img.shields.io/scrutinizer/g/olvlvl/doctrine-generators.svg)](https://scrutinizer-ci.com/g/olvlvl/doctrine-generators)
[![Code Coverage](https://img.shields.io/coveralls/olvlvl/doctrine-generators.svg)](https://coveralls.io/r/olvlvl/doctrine-generators)
[![Downloads](https://img.shields.io/packagist/dt/olvlvl/doctrine-generators.svg)](https://packagist.org/packages/olvlvl/doctrine-generators/stats)

__olvlvl/doctrine-generators__ provides generators for [Doctrine][]'s hydrators and proxies.

You can use these generators to create the hydrators and proxies required by your application
before building its artifact or container, so its ready to be used as soon as it's deployed.

**Disclaimer**: Only MongoDB documents are currently supported.

```php
<?php

use Doctrine\ODM\MongoDB\Configuration;
use olvlvl\DoctrineGenerators\Document\HydratorGenerator;
use olvlvl\DoctrineGenerators\Document\ProxyGenerator;

/* @var string $cacheDir */

// An excerpt of the configuration used to create the document manager
$config = new Configuration();
$config->setProxyDir("$cacheDir/Proxies");
$config->setProxyNamespace('Proxies');
$config->setAutoGenerateProxyClasses(Configuration::AUTOGENERATE_NEVER);
$config->setHydratorDir("$cacheDir/Hydrators");
$config->setHydratorNamespace('Hydrators');
$config->setAutoGenerateHydratorClasses(Configuration::AUTOGENERATE_NEVER);

/* @var \Doctrine\ODM\MongoDB\DocumentManager $dm */

$classes = (new HydratorGenerator)($dm);
$classes = (new ProxyGenerator)($dm);
```





----------





## Requirements

The package requires PHP 7.2 or later.





## Installation

The recommended way to install this package is through [Composer](http://getcomposer.org/):

	$ composer require olvlvl/doctrine-generators





## Testing

A container is available for local development. Enter the command `make test-container` to start the
container and open a shell. The command `make test` runs the test suite. Alternatively the command
`make test-coverage` runs the test suite and also creates an HTML coverage report in
`build/coverage`. Dependencies are installed as required.

The package is continuously tested by [Travis CI](http://about.travis-ci.org/).

[![Build Status](https://img.shields.io/travis/olvlvl/doctrine-generators.svg)](http://travis-ci.org/olvlvl/doctrine-generators)
[![Code Coverage](https://img.shields.io/coveralls/olvlvl/doctrine-generators.svg)](https://coveralls.io/r/olvlvl/doctrine-generators)





## License

**olvlvl/doctrine-generators** is licensed under the New BSD License - See the [LICENSE](LICENSE) file for details.





[Doctrine]: https://www.doctrine-project.org/
