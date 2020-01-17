<?php

namespace Siganushka\RBACBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SiganushkaRBACExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('rbac.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->findDefinition('rbac.node_collection');
        $definition->setArgument(1, $config);

        $container->setParameter('rbac.translation_domain', $config['translation_domain']);
    }

    public function prepend(ContainerBuilder $container)
    {
        if (!$container->hasExtension('twig')) {
            return;
        }

        $refl = new \ReflectionClass(\Siganushka\RBACBundle\SiganushkaRBACBundle::class);
        $path = \dirname($refl->getFileName()).'/Resources/views';

        $container->prependExtensionConfig('twig', ['paths' => [$path]]);
    }
}
