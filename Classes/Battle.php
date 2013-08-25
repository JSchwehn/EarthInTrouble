<?php
namespace oopTutorial\Classes;

class Battle
{
    const DRAW = false;

    /**
     * @var Army
     */
    private $_attacker = null;
    /**
     * @var Army
     */
    private $_defender = null;

    /**
     * @var int
     */
    private $_maxRounds = 10;

    /**
     * Constructor
     * Knows the following options
     * - attacker The attacking army
     * - defender The defending army
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
    }

    /**
     * @return bool|Army
     */
    public function fight()
    {
        $roundCounter = 0;
        $this->getAttacker()->respawn();
        $this->getDefender()->respawn();
        while ($this->getAttacker()->isAlive() && $this->getDefender()->isAlive()) {
            $this->getAttacker()->attack($this->getDefender());
            if ($this->getDefender()->isAlive()) {
                $this->getDefender()->attack($this->getAttacker());
            }
            $roundCounter++;
            if ($roundCounter > $this->getMaxRounds()) {
                return self::DRAW;
            }
        }

        return $this->getWinner();
    }

    /**
     * @return Army
     */
    public function getLooser()
    {
        if (!$this->getAttacker()->isAlive()) {
            return $this->getAttacker();
        }
        return $this->getDefender();
    }

    /**
     * @return Army
     */
    public function getWinner()
    {
        if ($this->getAttacker()->isAlive()) {
            return $this->getAttacker();
        }
        return $this->getDefender();
    }

    /**
     * @return Army
     */
    public function getAttacker()
    {
        return $this->_attacker;
    }

    /**
     * @param $attacker
     * @return Battle
     */
    public function setAttacker($attacker)
    {
        $this->_attacker = $attacker;
        return $this;
    }

    /**
     * @return Army
     */
    public function getDefender()
    {
        return $this->_defender;
    }

    /**
     * @param $defender
     * @return Battle
     */
    public function setDefender($defender)
    {
        $this->_defender = $defender;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxRounds()
    {
        return $this->_maxRounds;
    }

    /**
     * @param int $maxRounds
     * @return \\oopTutorial\Classes\Battle
     */
    public function setMaxRounds($maxRounds)
    {
        $this->_maxRounds = $maxRounds;
        return $this;
    }
}
