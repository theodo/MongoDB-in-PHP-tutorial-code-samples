<?php

namespace Documents;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/** @MongoDB\Document */
class Building
{
    /** @MongoDB\Id */
    public $id;

    /** @MongoDB\String */
    public $name;

    /** @MongoDB\String */
    public $city;

    /** @MongoDB\ReferenceMany(targetDocument="Documents\Measurement") */
    public $measurements;

    public function __construct($name, $city)
    {
        $this->name = $name;
        $this->city = $city;
        $this->measurements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function addMeasurement(Measurement $measurement)
    {
        $this->measurements[] = $measurement;
        $measurement->building = $this;
    }
}
