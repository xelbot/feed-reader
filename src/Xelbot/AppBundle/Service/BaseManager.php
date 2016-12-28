<?php

namespace Xelbot\AppBundle\Service;

use Doctrine\ORM\EntityManager;

class BaseManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param $object
     * @param bool $andFlush
     */
    public function save($object, $andFlush = true): void
    {
        $this->em->persist($object);
        if ($andFlush) {
            $this->em->flush();
        }
    }

    /**
     * @param $object
     * @param bool $andFlush
     */
    public function delete($object, $andFlush = true): void
    {
        $this->em->remove($object);
        if ($andFlush) {
            $this->em->flush();
        }
    }
}
