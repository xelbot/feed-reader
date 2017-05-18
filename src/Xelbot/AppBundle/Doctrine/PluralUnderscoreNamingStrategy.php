<?php

namespace Xelbot\AppBundle\Doctrine;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;

class PluralUnderscoreNamingStrategy extends UnderscoreNamingStrategy
{
    /**
     * {@inheritdoc}
     */
    public function classToTableName($className)
    {
        if (strpos($className, '\\') !== false) {
            $className = substr($className, strrpos($className, '\\') + 1);
        }

        $matches = [];
        if (preg_match('/(.*)([A-Z][^A-Z]+)$/', $className, $matches)) {
            $className = $matches[1] . Inflector::pluralize($matches[2]);
        }

        return parent::classToTableName($className);
    }
}
