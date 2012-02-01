<?php

namespace Documents;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/** @MongoDB\Document */
class Measurement
{
    /** @MongoDB\Id */
    public $id;

    /** @MongoDB\Int */
    public $captor1;

    /** @MongoDB\Int */
    public $captor2;

    /** @MongoDB\Date @MongoDB\UniqueIndex(order="asc") */
    public $datetime;

    /** @MongoDB\Int */
    public $year;

    /** @MongoDB\ReferenceOne(targetDocument="Documents\Building") */
    public $building;

    public function __construct($captors)
    {
        $this->captor1 = $captors['captor1'];
        $this->captor2 = $captors['captor2'];
    }

    /** @MongoDB\PrePersist */
    public function ensureDatetime()
    {
    	$this->datetime = new \DateTime();
    	$this->year = intval($this->datetime->format('Y'));
    }

    // makes old data consistent with new model at loading time
    /** @MongoDB\PreLoad */
    public function ensureYear()
    {
    	if (!$this->year && $this->datetime) {
	    	$this->year = intval($this->datetime->format('Y'));
    	}
    }
}
