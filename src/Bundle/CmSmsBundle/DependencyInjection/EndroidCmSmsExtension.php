<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\CmSms\Bundle\CmSmsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class EndroidCmSmsExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $clientDefinition = $container->getDefinition('endroid.cm_sms.client');
        $clientDefinition->setArguments([$config['product_token'], $config['defaults'], $config['delivery_phone_numbers'], $config['disable_delivery']]);
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        // Add the cmsms package to the assets configuration so the correct manifest is used
        $container->prependExtensionConfig('framework', [
            'assets' => [
                'packages' => [
                    'endroid_cm_sms' => [
                        'json_manifest_path' => '%kernel.project_dir%/public/bundles/endroidcmsms/build/manifest.json',
                    ],
                ],
            ],
        ]);
    }
}
