<?php
namespace USJ\GSuiteBundle\Manager;

use Google_Service_Directory;
use Google_Service_Directory_User;
use Psr\Log\LoggerAwareTrait;
use USJ\USJGSuiteBundle\Client\DomainSpecificClient;
use USJ\USJGSuiteBundle\Model\UserInterface;
use USJ\USJGSuiteBundle\Model\UserManagerInterface;

class UserManager implements UserManagerInterface
{
    use LoggerAwareTrait;

    /**
     * @var Google_Service_Directory
     */
    protected $directoryService;

    /**
     * @var DomainSpecificClient
     */
    private $client;

    /**
     * @param \Google_Client $client
     */
    public function __construct(\Google_Client $client)
    {
        $this->client = $client;
    }

    public function setSubject(string $subject): UserManagerInterface
    {
        $this->client->setSubject($subject);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function list($params): \Iterator
    {
        return $this->getDirectoryService()->users->listUsers($params);
    }


    /**
     * @param UserInterface $user
     */
    public function insert(UserInterface $user): void
    {
        $googleUser = $this->transform($user);

        $this->getDirectoryService()->users->insert($googleUser);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(UserInterface $user): void
    {
        $this->getDirectoryService()->users->delete($user->getGoogleEmail());
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById(string $id): void
    {
        $this->getDirectoryService()->users->delete($id);
    }

    /**
     * {@inheritdoc}
     */
    public function update(UserInterface $user): void
    {
        $this->getDirectoryService()->users->update($user->getGoogleEmail(), $this->transform($user));
    }

    /**
     * {@inheritdoc}
     */
    public function getById(string $id): Google_Service_Directory_User
    {
        $this->getDirectoryService()->users->get($id);
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $id): boolean
    {
        return (bool) $this->getDirectoryService()->users->get($id);
    }

    /**
     * @return Google_Service_Directory
     */
    private function getDirectoryService()
    {
        if (!$this->directoryService) {
            $this->directoryService = new Google_Service_Directory($this->client);
        }

        return $this->directoryService;
    }

    private function transform(UserInterface $user): Google_Service_Directory_User
    {
        $googleUser = new Google_Service_Directory_User();

        $googleUser->setPrimaryEmail($user->getGoogleEmail());
        $googleUser->setName($user->getGoogleDirectoryName());
        $googleUser->setHashFunction($user->getGooglePasswordHashFunc());
        $googleUser->setPassword($user->getGooglePassword());

        return $googleUser;
    }
}
