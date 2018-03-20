<?php


namespace USJ\GSuiteBundle\Model;


interface GroupManagerInterface
{
    const ROLE_MEMBER = "MEMBER";
    const ROLE_MANAGER = "MANAGER";

    public function list($params): \Iterator;

    public function remove($groupEmail): void;

    public function get($groupEmail): \Google_Service_Directory_Group;

    public function listMembersInGroup($groupEmail): \Iterator;

    public function isUserInGroup($groupEmail, UserInterface $user): bool;

    public function findGroupsByUser(UserInterface $user): \Iterator;

    public function addUserToGroup($groupEmail, UserInterface $user, $role = "MEMBER"): void;

    public function removeUserFromGroup($groupEmail, UserInterface $user): void;
}