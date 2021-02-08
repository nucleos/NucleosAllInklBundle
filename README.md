NucleosAllInklBundle
====================
[![Latest Stable Version](https://poser.pugx.org/nucleos/allinkl-bundle/v/stable)](https://packagist.org/packages/nucleos/allinkl-bundle)
[![Latest Unstable Version](https://poser.pugx.org/nucleos/allinkl-bundle/v/unstable)](https://packagist.org/packages/nucleos/allinkl-bundle)
[![License](https://poser.pugx.org/nucleos/allinkl-bundle/license)](https://packagist.org/packages/nucleos/allinkl-bundle)

[![Total Downloads](https://poser.pugx.org/nucleos/allinkl-bundle/downloads)](https://packagist.org/packages/nucleos/allinkl-bundle)
[![Monthly Downloads](https://poser.pugx.org/nucleos/allinkl-bundle/d/monthly)](https://packagist.org/packages/nucleos/allinkl-bundle)
[![Daily Downloads](https://poser.pugx.org/nucleos/allinkl-bundle/d/daily)](https://packagist.org/packages/nucleos/allinkl-bundle)

[![Continuous Integration](https://github.com/nucleos/NucleosAllInklBundle/workflows/Continuous%20Integration/badge.svg?event=push)](https://github.com/nucleos/NucleosAllInklBundle/actions?query=workflow%3A"Continuous+Integration"+event%3Apush)
[![Code Coverage](https://codecov.io/gh/nucleos/NucleosAllInklBundle/graph/badge.svg)](https://codecov.io/gh/nucleos/NucleosAllInklBundle)
[![Type Coverage](https://shepherd.dev/github/nucleos/NucleosAllInklBundle/coverage.svg)](https://shepherd.dev/github/nucleos/NucleosAllInklBundle)

This bundle provides a wrapper for using [all-inkl API] inside symfony applications.

## Installation

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

```
composer require nucleos/allinkl-bundle
```

### Sonata block integration (optional)

If you want to use sonata blocks to use widgets:

```
composer require sonata-project/block-bundle
```

### Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles in `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Nucleos\AllInklBundle\NucleosAllInklBundle::class => ['all' => true],
];
```

## Usage

```twig
{# template.twig #}

{{ sonata_block_render({ 'type': 'nucleos_allinkl.block.space_statistic' }, {
    'login':    'XXX',
    'password': 'XXX'
}) }}
```

## Notes

You can't use this bundle properly, if you have 2FA enabled.

## License

This bundle is under the [MIT license](LICENSE.md).

[all-inkl API]: http://kasapi.kasserver.com/

