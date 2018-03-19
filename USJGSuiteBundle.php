<?php


namespace USJ\GSuiteBundle;


use Symfony\Component\HttpKernel\Bundle\Bundle;
use USJ\GSuiteBundle\DependencyInjection\USJGSuiteExtension;

class USJGSuiteBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new USJGSuiteExtension();
    }
}
