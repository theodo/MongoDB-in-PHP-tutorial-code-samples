<?php

namespace Documents;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/** @MongoDB\Document */
class Measurement
{
    /** @MongoDB\Id */
    private $id;

    /** @MongoDB\Int */
    private $captor1;

    /** @MongoDB\Int */
    private $captor2;

    /** @MongoDB\Date */
    private $datetime;

    /** @MongoDB\Int */
    private $year;

    public function __construct($captor1, $captor2)
    {
        $this->captor1 = $captor1;
        $this->captor2 = $captor2;
    }

    /** @MongoDB\PrePersist */
    public function ensureDatetime()
    {
    	$this->datetime = new \DateTime();
    	$this->year = intval($this->datetime->format('Y'));
    	var_dump($this->year);
    }

    /** @MongoDB\PreLoad */
    public function ensureYear()
    {
    	if (!$this->year && $this->datetime) {
	    	$this->year = intval($this->datetime->format('Y'));
    	}
    }
}
