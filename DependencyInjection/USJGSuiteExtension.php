<?php


namespace USJ\GSuiteBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use USJ\GSuiteBundle\Manager\UserManager;
use USJ\USJGSuiteBundle\Model\UserManagerInterface;

class USJGSuiteExtension extends Extension
{

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->loadClientConfig($config, $container);
    }

    protected function loadClientConfig(array $config, ContainerBuilder $container)
    {
        // create default client
        $defaultClient = $this->createClientDefinition($config['clients']['default']);

        $container->addDefinitions([
            'usj_gsuite.client.default' => $defaultClient,
        ]);

        $container->addAliases([
            'usj_gsuite.client' => 'usj_gsuite.client.default',
            \Google_Client::class => 'usj_gsuite.client.default'
        ]);

        $userDefinition = $this->createUserDefinition('default', $container);

        $container->addDefinitions(['usj_gsuite.default.user' => $userDefinition]);

        $container->addAliases([
            'usj_gsuite.user' => 'usj_gsuite.default.user',
            UserManagerInterface::class => 'usj_gsuite.default.user'
        ]);
    }

    protected function createUserDefinition($clientId, ContainerBuilder $container)
    {
        $definition = new Definition(UserManager::class);
        $definition->setArgument(0, new Reference(sprintf('usj_gsuite.client.%s', $clientId)));
        $container->setDefinition(sprintf('usj_gsuite.%s.user', $clientId), $definition);

        return $definition;
    }

    private function createClientDefinition($config): Definition
    {
        $definition = new Definition(\Google_Client::class);

        $definition->addMethodCall('setAuthConfig', [$config['key']]);
        $definition->addMethodCall('setSubject', [$config['subject']]);
        $definition->addMethodCall('setScopes', [$config['scopes']]);

        return $definition;
    }

    protected function getDefaultScopes()
    {
        return [
            'https://www.googleapis.com/auth/admin.directory.user',
            'https://www.googleapis.com/auth/admin.directory.group'
        ];
    }
}
