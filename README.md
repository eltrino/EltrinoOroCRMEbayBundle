# OroCRM eBay Integration

This bundle adds additional channel to OroCRM which allows import orders from eBay, view orders with all order info like ordered items, addresses, other customer data provided by eBay via eBay API and retrieved by the bundle.

Eltrino is working on improvements for this bundle as well as on support for Amazon and other popular platforms and marketplaces.

## Requirements

eBay integration bundle supports OroCRM version 1.3 or above. Additional requirement is Guzzle version 3.7.

## Installation

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

In order to preserve data consistency, you will need to load data from Buyer Connector first.
Doing this facilitates loading of the correct user data to imported orders.
To achieve this, follow provided instruction:

1. Open `System > Integrations > Manage Integrations`
2. Create (or edit existing) integration with type **Ebay**
3. Fill in all the required data
4. Make sure none of the connector checkboxes are checked
5. Click **Save** button on the top right of the page. 
6. Mark  **Buyer connector** checkbox
7. **Save** the integration
8. Mark **Order connector** checkbox
9. **Save** the integration once again

We've created the feature request for allowing the ordering of the connectors by some parameter to ensure the correct data loading order. As soon as (and if) these changes are implemented this instruction will be updated.
You can follow the news for request [here][2]

[1]: http://www.orocrm.com/marketplace/oro-crm/package/orocrm-ebay-integration
[2]: http://www.orocrm.com/forums/topic/ordering-the-integration-connectors-by-parameter

## Contributing

We welcome all kinds of contributions in the form of bug reporting, patches submition, feature requests or documentation enhancement. Please refer to our [guidelines for contributing](https://github.com/eltrino/EltrinoOroCRMEbayBundle/blob/master/Contributing.md) if you wish to be a part of the project.