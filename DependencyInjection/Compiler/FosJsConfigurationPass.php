<?php
namespace Eltrino\OroCrmEbayBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class FosJsConfigurationPass implements CompilerPassInterface
{
    const FOS_JS_CONF_SERVICE_KEY = 'fos_js_routing.extractor';

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(self::FOS_JS_CONF_SERVICE_KEY)) {
            return;
        }

        $container->getDefinition('fos_js_routing.extractor')
            ->replaceArgument(1, array(0 => "[oro_*, eltrino_*]"));
    }
}
