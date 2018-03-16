<?php


namespace USJ\GSuiteBundle\Tests\DependencyInjection;


use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;
use USJ\GSuiteBundle\DependencyInjection\USJGSuiteExtension;
use USJ\USJGSuiteBundle\Model\UserManagerInterface;

class USJGSuiteExtensionTest extends TestCase
{
    /**
     * @var ContainerBuilder
     */
    protected $configuration;

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testKeyLocationNotSetThrowException()
    {
        $loader = new USJGSuiteExtension();
        $config = $this->getBasicConfig();

        unset($config['clients']['default']['key']);
        $loader->load([$config], new ContainerBuilder());
    }

    public function testCreatedDefaultUserService()
    {
        $this->createBasicConfig();
        $this->assertHasDefinition('usj_gsuite.default.user');
        $this->assertHasAlias('usj_gsuite.user');
        $this->assertHasAlias(UserManagerInterface::class);
    }

    public function testCreateDefaultClient()
    {
        $this->createBasicConfig();
        $this->assertHasDefinition('usj_gsuite.client.default');
        $this->assertHasAlias('usj_gsuite.client');
        $this->assertHasAlias(\Google_Client::class);
    }

    public function testCreateFullConfig()
    {
        $this->createFullConfig();
        $this->assertHasDefinition('usj_gsuite.client.another');
        $this->assertHasDefinition('usj_gsuite.another.users');
    }

    protected function getBasicConfig()
    {
$yaml = <<<EOF
clients:
    default:
        key: key_location
        subject: example@example.org
        scopes:
            - https://www.googleapis.com/auth/admin.directory.user
            - https://www.googleapis.com/auth/admin.directory.group
EOF;

        $parser = new Parser();
        return $parser->parse($yaml);
    }

    protected function getFullConfig()
    {
        $yaml = <<<EOF
clients:
    default:
        key: key_location
        subject: example@example.org
    another:
        key: key_location
        subject: example@example-two.org
EOF;

        $parser = new Parser();
        return $parser->parse($yaml);
    }

    private function createFullConfig()
    {
        $this->configuration = new ContainerBuilder();
        $loader = new USJGSuiteExtension();
        $config = $this->getFullConfig();
        $loader->load([$config], $this->configuration);
    }

    private function createBasicConfig()
    {
        $this->configuration = new ContainerBuilder();
        $loader = new USJGSuiteExtension();
        $config = $this->getBasicConfig();
        $loader->load([$config], $this->configuration);
    }

    protected function assertHasAlias($id)
    {
        $this->assertTrue($this->configuration->hasAlias($id));
    }

    protected function assertHasDefinition($id)
    {
        $this->assertTrue($this->configuration->hasDefinition($id));
    }
}
