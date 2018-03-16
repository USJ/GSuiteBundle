<?php


namespace USJ\GSuiteBundle\Tests\Manager;


use PHPUnit\Framework\TestCase;
use USJ\GSuiteBundle\Manager\UserManager;
use USJ\GSuiteBundle\Tests\TestUser;

class UserManagerTest extends TestCase
{
    public function testTransformUserWhenInsert()
    {
        $userMock = $this->createMock(TestUser::class);
        $userMock->method('getGoogleDirectoryName')->expects($this->once());

        $subject = new UserManager($this->createMock(\Google_Client::class));

        $subject->insert($userMock);
    }
}
