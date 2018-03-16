<?php


namespace USJ\GSuiteBundle\Tests;


use USJ\GSuiteBundle\Model\UserInterface;

class TestUser implements UserInterface
{

    /**
     * @return \Google_Service_Directory_UserName
     */
    public function getGoogleDirectoryName(): \Google_Service_Directory_UserName
    {
        $name = new \Google_Service_Directory_UserName();
        $name->setFullName('full');

        return $name;
    }

    /**
     * @return string
     */
    public function getGoogleEmail(): string
    {
        return 'example@example.com';
    }

    /**
     * @return string
     */
    public function getGooglePasswordHashFunc(): string
    {
        return 'md5';
    }

    /**
     * @return string
     */
    public function getGooglePassword(): string
    {
        return '123';
    }
}
