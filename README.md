OroCRM eBay Integration
========================

This bundle adds additional channel to OroCRM which allows import orders from eBay, view orders with all order info like ordered items, addresses, other customer data provided by eBay via eBay API and retrieved by the bundle.

Eltrino is working on improvements for this bundle as well as on support for Amazon and other popular platforms and marketplaces.

Requirements
------------

eBay integration bundle supports OroCRM version 1.0 or above. Additional requirement is Guzzle version 3.7.

Installation
------------

### Marketplace

Follow `System > Package Manager` to install it from [OroCRM Marketplace][1]

### Composer

Add as dependency in composer
```bash
composer require eltrino/orocrm-ebay-bundle:dev-master
```

In addition you will need to run platform update
```bash
php app/console oro:platform:update
```

[1]: http://www.orocrm.com/marketplace/oro-crm/package/orocrm-ebay-integration
