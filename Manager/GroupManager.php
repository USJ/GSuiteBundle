<?php


namespace USJ\GSuiteBundle\Manager;


use Google_Service_Directory;
use USJ\GSuiteBundle\Model\GroupManagerInterface;
use USJ\GSuiteBundle\Model\UserInterface;

class GroupManager implements GroupManagerInterface
{
    /**
     * @var Google_Service_Directory
     */
    protected $directoryService;

    /**
     * @var \Google_Client
     */
    private $client;

    public function __construct(\Google_Client $client)
    {
        $this->client = $client;
    }

    public function list($params): \Iterator
    {
        return $this->getDirectoryService()->groups->listGroups($params);
    }

    public function remove($groupEmail): void
    {
        $this->getDirectoryService()->groups->delete($groupEmail);
    }

    public function get($groupEmail): \Google_Service_Directory_Group
    {
        return $this->getDirectoryService()->groups->get($groupEmail);
    }

    public function listMembersInGroup($groupEmail): \Iterator
    {
        $nextPageToken = null;

        do {
            $result = $this->getDirectoryService()->members->listMembers(
                $groupEmail,
                $nextPageToken ? ['pageToken' => $nextPageToken] : []
            );

            foreach ($result as $item) {
                yield $item;
            }
        } while ($nextPageToken = $result->nextPageToken);
    }

    public function isUserInGroup($groupEmail, UserInterface $user): bool
    {
        return (bool)$this->getDirectoryService()->members
            ->hasMember($groupEmail, $user->getGoogleEmail())
            ->getIsMember();
    }

    public function findGroupsByUser(UserInterface $user): \Iterator
    {
        return $this->getDirectoryService()->groups->listGroups(['userKey' => $user->getGoogleEmail()]);
    }

    public function addUserToGroup($groupEmail, UserInterface $user, $role = "MEMBER"): void
    {
        $member = new \Google_Service_Directory_Member();
        $member->setEmail($user->getGoogleEmail());
        $member->setRole($role);

        $this->getDirectoryService()->members->insert(
            $groupEmail,
            $member
        );
    }

    public function removeUserFromGroup($groupEmail, UserInterface $user): void
    {
        $this->getDirectoryService()->members->delete($groupEmail, $user->getGoogleEmail());
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
}
