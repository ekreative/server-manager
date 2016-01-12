<?php


namespace AppBundle\Form\Model;

use AppBundle\Entity\Framework;
use Symfony\Component\Validator\Constraints as Assert;

class Search
{

    /**
     * @var string
     *
     */
    protected $name;

    /**
     * @var Framework
     *
     */
    protected $framework;

    function __construct()
    {
    }

    /**
     * @param string $name
     * @return Search
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Framework
     */
    public function getFramework()
    {
        return $this->framework;
    }

    /**
     * @param Framework $framework
     * @return Search
     */
    public function setFramework($framework)
    {
        $this->framework = $framework;
        return $this;
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    function __toString()
    {
        return trim($this->getName());
    }


}