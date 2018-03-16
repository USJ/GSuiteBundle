<?php


namespace USJ\USJGSuiteBundle\Client;


class Registry
{
    protected $clients;

    public function addClient($name, DomainSpecificClient $client)
    {
        $this->clients[$name] = $client;
    }

    public function get($name)
    {
        return $this->clients[$name];
    }
}
