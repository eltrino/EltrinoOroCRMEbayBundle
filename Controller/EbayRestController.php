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

namespace Eltrino\OroCrmEbayBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Oro\Bundle\IntegrationBundle\Entity\Channel;
use Oro\Bundle\IntegrationBundle\Provider\ConnectorInterface;

use Eltrino\OroCrmEbayBundle\Entity\EbayRestTransport;

/**
 * @Route("/rest")
 */
class EbayRestController extends Controller
{
    /**
     * @Route("/check", name="eltrino_ebay_rest_check")
     */
    public function checkAction(Request $request)
    {

        $transport = $this->get('eltrino_ebay.ebay_rest_transport');
        $data = null;

        if ($id = $request->get('id', false)) {
            $data = $this->get('doctrine.orm.entity_manager')->find($transport->getSettingsEntityFQCN(), $id);
        }

        $form = $this->get('form.factory')
            ->createNamed('rest-check', $transport->getSettingsFormType(), $data, ['csrf_protection' => false]);
        $form->submit($request);

        $ebayRestClientFactory = $this->get('eltrino_ebay.ebay_rest_client.factory');
        $actionFactory         = $this->get('eltrino_ebay.action.factory');
        $filtersFactory        = $this->get('eltrino_ebay.filters.factory');

        $ebayRestClient = $ebayRestClientFactory->create(
            $data->getWsdlUrl(),
            $data->getDevId(),
            $data->getAppId(),
            $data->getCertId(),
            $data->getAuthToken()
        );

        $action = $actionFactory->createCheckConnectionAction($ebayRestClient);

        $compositeFilter = $filtersFactory->createCompositeFilter();

        $result = $action->execute($compositeFilter);

        return new JsonResponse(
            [
                'success' => $result
            ]
        );
    }
}
