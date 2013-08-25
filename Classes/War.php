<?php
namespace oopTutorial\Classes;

class War
{

    /**
     * @var array
     */
    private $_armies = array();

    /**
     * @var Army
     */
    private $_currentAttacker = null;
    /**
     * @var Army
     */
    private $_currentDefender = null;

    /**
     * Constructor
     * Knows the following options
     * - armies
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
     * Main loop
     *
     * @throws \Exception
     */
    public function fight()
    {
        if (count($this->getArmies()) <= 1) {
            throw new \Exception('Not enough players');
        }

        while (count($this->getArmies()) !== 1) {
            $battle = new Battle(array(
                    'attacker' => $this->selectAttacker(),
                    'defender' => $this->selectDefender()
                )
            );
            if ($winner = $battle->fight()) {
                echo "The Battle has won by " . $winner->getName() . "<br><br>";
                $this->removeArmy($battle->getLooser());

            } else {
                echo "Die Schlacht war unentschieden<br>";
            }
            unset($this->_currentAttacker);
            unset($this->_currentDefender);
        }

        return array_pop($this->_armies);
    }

    private function removeArmy(Army $army)
    {
        foreach ($this->_armies as $key => $globalArmy) {
            /** @var $globalArmy Army */
            if ($globalArmy->getName() === $army->getName()) {
                unset($this->_armies[$key]);
            }
        }
    }

    private function selectAttacker()
    {
        shuffle($this->_armies);
        $this->setCurrentAttacker($this->_armies[0]);
        echo "Attacking army is: " . $this->_currentAttacker->getName() . "<br>";
        return $this->_armies[0];
    }

    private function selectDefender()
    {
        $leftArmies = array();
        foreach ($this->_armies as $army) {
            /** @var $army Army */
            if ($army->getName() !== $this->_currentAttacker->getName()) {
                $leftArmies[] = $army;
            }
        }
        $this->setCurrentDefender($leftArmies[0]);
        echo "Defending army is: " . $this->_currentDefender->getName() . "<br>";
        return $leftArmies[0];
    }

    private function setCurrentAttacker(Army $army)
    {
        $this->_currentAttacker = $army;
        return $this;
    }

    private function setCurrentDefender(Army $army)
    {
        $this->_currentDefender = $army;
        return $this;
    }

    /**
     * @return array
     */
    public function getArmies()
    {
        return $this->_armies;
    }

    public function setArmies($armies)
    {
        $this->_armies = $armies;
        return $this;
    }
}
