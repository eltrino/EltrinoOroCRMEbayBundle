# OroCRM eBay Integration

This bundle adds additional channel to the OroCRM, allowing to import orders from eBay, view orders with all order info such as ordered items, addresses and other customer data provided by eBay via eBay API and retrieved by the bundle.

Eltrino team is working on improvements for this bundle, as well as on support for Amazon and other popular platforms and marketplaces.

## Requirements

- supports OroCRM version 1.3 or above; 
- Guzzle version 3.7.

## Installation

### Marketplace

Navigate to `System > Package Manager` to install it from [OroCRM Marketplace][1]

### Composer

Add as dependency in composer:
```bash
composer require eltrino/orocrm-ebay-bundle:dev-master
```

In addition, you will need to run platform update:
```bash
php app/console oro:platform:update
```

In order to preserve data consistency, load data from the Buyer Connector first.
This action facilitates loading of the correct user data to imported orders.
To achieve this, follow the provided instruction:

1. Open `System > Integrations > Manage Integrations`.
2. Create (or edit existing) integration with **Ebay** type.
3. Provide all the required data.
4. Make sure none of the connector check boxes are selected.
5. Click **Save** on the top right corner of the page. 
6. Select **Buyer connector** check box.
7. **Save** the integration.
8. Select **Order connector** check box.
9. **Save** the integration once again.

We've created the feature request to enable connector ordering by the certain parameters to ensure the correct data loading order. As soon as (and if) these changes are implemented, this instruction will be updated.
You can follow the news for request [here][2]

[1]: http://www.orocrm.com/marketplace/oro-crm/package/orocrm-ebay-integration
[2]: http://www.orocrm.com/forums/topic/ordering-the-integration-connectors-by-parameter

## Contributing

We welcome all kinds of contributions in the form of bug reporting, patches submition, feature requests or documentation enhancement. Please refer to our [guidelines for contributing](https://github.com/eltrino/EltrinoOroCRMEbayBundle/blob/master/Contributing.md) if you wish to be a part of the project.
