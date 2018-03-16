<?php


namespace USJ\GSuiteBundle\Model;


interface UserInterface
{
    public function getGoogleDirectoryName(): \Google_Service_Directory_UserName;

    public function getGoogleEmail(): string;

    public function getGooglePasswordHashFunc(): string;

    public function getGooglePassword(): string;
}
