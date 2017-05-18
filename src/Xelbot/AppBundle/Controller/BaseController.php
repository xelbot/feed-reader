<?php

namespace Xelbot\AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEm(): EntityManager
    {
        return $this->get('doctrine.orm.default_entity_manager');
    }
}
