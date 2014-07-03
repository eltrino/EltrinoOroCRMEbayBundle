<?php
/*
 * Copyright (c) 2014 Eltrino LLC (http://eltrino.com)
 *
 * Licensed under the Open Software License (OSL 3.0).
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://opensource.org/licenses/osl-3.0.php
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@eltrino.com so we can send you a copy immediately.
 */
namespace Eltrino\OroCrmEbayBundle\Model\Order;

use Eltrino\OroCrmEbayBundle\Entity\AddressFactory;
use Eltrino\OroCrmEbayBundle\Entity\Customer;
use Eltrino\OroCrmEbayBundle\Entity\Order;
use Eltrino\OroCrmEbayBundle\Entity\OrderItem;
use Eltrino\OroCrmEbayBundle\Entity\User;
use Eltrino\OroCrmEbayBundle\Entity\UserFactory;
use Eltrino\OroCrmEbayBundle\Model\Customer\CustomerAddress;
use SimpleXMLElement;
use Doctrine\Common\Collections\ArrayCollection;

use Eltrino\OroCrmEbayBundle\Model\OrderItem\ItemInfo;
use Eltrino\OroCrmEbayBundle\Model\OrderItem\Transaction;

class OrderFactory
{
    /**
     * @var UserFactory
     */
    private $userFactory;

    /**
     * @var AddressFactory
     */
    private $addressFactory;

    public function __construct(UserFactory $userFactory, AddressFactory $addressFactory)
    {
        $this->userFactory = $userFactory;
        $this->addressFactory = $addressFactory;
    }

    /**
     * Create Order
     */
    public function createOrder(SimpleXMLElement $data)
    {
        $ebayOrderId         = (string) $data->OrderID;
        $buyerUserId         = (string) $data->BuyerUserID;
        $orderStatus         = (string) $data->OrderStatus;
        $amountPaid          = (string) $data->AmountPaid;
        $currencyId          = (string) $data->AmountPaid['currencyID'];
        $salesTaxPercent     = (string) $data->ShippingDetails->SalesTax->SalesTaxPercent;
        $salesTaxAmount      = (string) $data->ShippingDetails->SalesTax->SalesTaxAmount;
        $shippingService     = (string) $data->ShippingDetails->ShippingServiceOptions->ShippingService;
        $shippingServiceCost = (string) $data->ShippingDetails->ShippingServiceOptions->ShippingServiceCost;
        $paymentMethods      = (string) $data->PaymentMethods;
        $sellerUserId        = (string) $data->SellerUserID;
        $sellerEmail         = (string) $data->SellerEmail;
        $subtotal            = (string) $data->Subtotal;
        $total               = (string) $data->Total;

        $buyer = $this->prepareBuyer($data);
        $items    = $this->prepareOrderItems($data->TransactionArray->Transaction);

        $shipping     = new Shipping($salesTaxPercent, $salesTaxAmount, $shippingService, $shippingServiceCost);
        $payment      = new Payment($amountPaid, $currencyId, $paymentMethods);
        $orderDetails = new OrderDetails($orderStatus, $subtotal, $total, $sellerEmail, $payment, $shipping);

        return new Order($ebayOrderId, $buyerUserId, $sellerUserId, $orderDetails, $items, $buyer);
    }

    private function prepareOrderItems($transactionItems)
    {
        $orderItems = new ArrayCollection();

        foreach ($transactionItems as $transactionItem) {
            $buyerEmail            = (string) $transactionItem->Buyer->Email;
            $itemId                = (string) $transactionItem->Item->ItemID;
            $itemSite              = (string) $transactionItem->Item->Site;
            $itemTitle             = (string) $transactionItem->Item->Title;
            $sku                   = (string) $transactionItem->Item->SKU;
            $conditionDisplayName  = (string) $transactionItem->Item->ConditionDisplayName;
            $quantityPurchased     = (string) $transactionItem->QuantityPurchased;
            $transactionId         = (string) $transactionItem->TransactionID;
            $transactionPrice      = (string) $transactionItem->TransactionPrice;
            $currencyId            = (string) $transactionItem->TransactionPrice['currencyID'];
            $totalTaxAmount        = (string) $transactionItem->Taxes->TotalTaxAmount;
            $orderLineItemId       = (string) $transactionItem->OrderLineItemID;

            $itemInfo    = new ItemInfo($itemId, $itemSite, $itemTitle, $sku, $conditionDisplayName);
            $transaction = new Transaction($transactionId, $transactionPrice, $currencyId, $totalTaxAmount);

            $orderItems->add(new OrderItem($buyerEmail, $itemInfo, $quantityPurchased, $transaction, $orderLineItemId));
        }

        return $orderItems;
    }

    private function prepareBuyer($data)
    {
        $buyerUserId     = (string) $data->BuyerUserID;
        $EIASToken       = (string) $data->EIASToken;
        $name            = (string) $data->ShippingAddress->Name;
        $street1         = (string) $data->ShippingAddress->Street1;
        $street2         = (string) $data->ShippingAddress->Street2;
        $city            = (string) $data->ShippingAddress->CityName;
        $stateOrProvince = (string) $data->ShippingAddress->StateOrProvince;
        $countryCode     = (string) $data->ShippingAddress->Country;
        $countryName     = (string) $data->ShippingAddress->CountryName;
        $phone           = (string) $data->ShippingAddress->Phone;
        $postalCode      = (string) $data->ShippingAddress->PostalCode;

        $buyer = $this->userFactory->create($buyerUserId, $EIASToken);
        $address = $this->addressFactory->create($name, $phone, $street1, $street2, $city,
            $stateOrProvince, $countryCode, $countryName, $postalCode);
        $buyer->addAddress($address);
        return $buyer;
    }
}
