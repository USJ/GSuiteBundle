<?php


namespace USJ\USJGSuiteBundle\Model;


interface UserManagerInterface
{
    /**
     * @param string $id
     *
     * @return UserManagerInterface
     */
    public function setSubject(string $id): UserManagerInterface;

    /**
     * Add user to Google directory
     *
     * @param UserInterface $user
     */
    public function insert(UserInterface $user): void;

    /**
     * List users inside Google directory
     *
     * @param $params
     *
     * @return \Iterator
     */
    public function list($params): \Iterator;

    /**
     * @param string $id
     *
     * @return \Google_Service_Directory_User
     */
    public function getById(string $id): \Google_Service_Directory_User;

    /**
     * Update user with new data
     *
     * @param UserInterface $user
     */
    public function update(UserInterface $user): void;

    /**
     * Remove user from Google directory
     *
     * @param UserInterface $user
     */
    public function delete(UserInterface $user): void;

    /**
     * Remove user from Google directory by an identifier
     *
     * @param $id
     */
    public function deleteById(string $id): void;

    /**
     * Check if user with id exists
     *
     * @param string $id
     *
     * @return bool
     */
    public function has(string $id): boolean;
}
