<?php

// src/Acme/StoreBundle/Document/Product.php
namespace Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Product
{
    /**
     * @MongoDB\Id
     */
    public $id;

    /**
     * @MongoDB\String
     */
    public $name;

    /**
     * @MongoDB\Float
     */
    public $price;
}