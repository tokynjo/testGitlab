<?php
/**
 * Created by PhpStorm.
 * User: JOCELYN
 * Date: 10/08/2017
 * Time: 13:04
 */

namespace AppBundle\Services;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

class ContainerService
{
    public $em;
    public $container;

    public function __construct(EntityManager $em,ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

}