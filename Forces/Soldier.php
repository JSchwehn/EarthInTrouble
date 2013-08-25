<?php
namespace oopTutorial\Forces;

/**
 * User: JSchwehn
 * Date: 25.08.13
 * Time: 09:01
 */
class Soldier
{
    /**
     * @var int
     */
    private $_armor = 1;

    /**
     * @var int
     */
    private $_strength = 1;

    /**
     * @var bool
     */
    private $_isAlive = true;

    /**
     * @var String
     */
    private $_id = null;

    /**
     * Soldier Unit. Can attack and can receive damage.
     * Takes the following options
     *      - armor optional
     *      - strength optional
     *
     * $soldier = new Soldier(
     *                  array('strength'=>1),
     *                  array('armor' = 1)
     *              );
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        foreach ($options as $option => $value) {
            $setterMethod = 'set' . ucfirst($option);
            if (method_exists($this, $setterMethod)) {
                $this->$setterMethod($value);
            }
        }
        // wenn keine Kennung gesetzt wurde, eine erzeugen.
        if (is_null($this->getId())) {
            $this->setId(uniqid('s', true));
        }
    }

    public function disable()
    {
        $this->setIsAlive(false);
    }

    /**
     * Set the armor value for an given unit
     *
     * @param $armor
     * @throws \OutOfBoundsException
     * @return Soldier
     */
    public function setArmor($armor)
    {
        if (!filter_var($armor, FILTER_VALIDATE_INT)) {
            throw new \OutOfBoundsException('Parameter Error: A non integer value was provided.');
        }
        $this->_armor = $armor;
        return $this;
    }

    /**
     * Set the stength value for a military unit
     *
     * @param $str
     * @throws \OutOfBoundsException
     * @return Soldier
     */
    public function setStrength($str)
    {
        if (!filter_var($str, FILTER_VALIDATE_INT)) {
            throw new \OutOfBoundsException('Parameter Error: A non integer value was provided.');
        }
        $this->_strength = $str;
        return $this;
    }

    /**
     * @return Integer
     */
    public function getStrength()
    {
        return $this->_strength;
    }

    /**
     * @return Integer
     */
    public function getArmor()
    {
        return $this->_armor;
    }

    /**
     * @return String
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param String $id
     */
    private function setId($id)
    {
        $this->_id = $id;
    }

    public function isAlive()
    {
        return $this->_isAlive;
    }

    public function setIsAlive($isAlive)
    {
        $this->_isAlive = $isAlive;
    }
}
