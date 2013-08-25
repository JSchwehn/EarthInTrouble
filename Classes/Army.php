<?php
namespace oopTutorial\Classes;

class Army
{

    private $_precision = 90;
    private $_forces = array(array('soldiers' => array()));
    private $_name = '';
    private $_overallArmor = null;
    private $_overallStrength = null;

    private $_initalForces = array();

    public function __construct(array $options)
    {
        foreach ($options as $option => $value) {
            $setterMethod = 'set' . ucfirst($option);
            if (method_exists($this, $setterMethod)) {
                $this->$setterMethod($value);
            }
        }
    }

    public function getPrecision()
    {
        return $this->_precision;
    }

    public function setPrecision($precision)
    {
        $this->_precision = $precision;
        return $this;
    }

    public function getForces()
    {
        return $this->_forces;
    }

    public function setForces($forces)
    {
        $this->_forces       = $forces;
        $this->_initalForces = $forces;
        return $this;
    }

    public function respawn()
    {
        $this->setForces($this->_initalForces);
    }

    /**
     * @return bool
     */
    public function isAlive()
    {
        if ($this->getOverallStrength() >= 1 || $this->getOverallArmor() >= 1) {
            return true;
        }
        return false;
    }

    public function attack(Army $defendingArmy)
    {
        $this->updateArmy();
        $damage = $this->calculate($this->getOverallStrength(), $this->getPrecision());
        $defendingArmy->handleHit($damage);

    }

    public function handleHit($damageDealt)
    {
        $armor      = $this->calculate($this->getOverallArmor(), $this->getPrecision());
        $realDamage = $damageDealt - $armor;
        if ($realDamage < 0) {
            $realDamage = 0;
        }
        echo "Army " . $this->getName(
        ) . " has been hit with " . $damageDealt . " damage but the armor blocked $armor. Damage is reduced to $realDamage.<br>";
        $this->reduceUnits($realDamage);
    }

    private function reduceUnits($damageReceived)
    {
        $tmpStrengthSum = 0;
        foreach ($this->getForces() as $force) {
            /** @var $unit \oopTutorial\Forces\Soldier */
            foreach ($force as $unit) {
                if (!$unit->isAlive()) {
                    continue;
                }
                $tmpStrengthSum += $unit->getStrength();
                if ($damageReceived >= $tmpStrengthSum) {
                    $unit->disable();
                } else {
                    continue 2;
                }
            }
        }
        $this->updateArmy();
    }

    private function updateArmy()
    {
        $strength = 0;
        $armor    = 0;
        foreach ($this->_forces as $force) {
            /** @var $unit \oopTutorial\Forces\Soldier */
            foreach ($force as $key => $unit) {
                if ($unit->isAlive()) {
                    $strength += $unit->getStrength();
                    $armor += $unit->getArmor();
                }
            }
        }
        $this->setOverallArmor($armor);
        $this->setOverallStrength($strength);
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }


    private function calculate($base, $chance)
    {
        if (!filter_var($base, FILTER_VALIDATE_INT)) {
            throw new \OutOfBoundsException('Parameter Error: A non integer value was provided.');
        }
        if (!filter_var(
            $chance,
            FILTER_VALIDATE_INT,
            array('options' => array('min_range' => 1, 'max_range' => 100))
        )
        ) {
            throw new \OutOfBoundsException('Allowed range is between 1 and 100.');
        }
        // Hier müssen wir ggf den Algorithmus anpassen. und den unGenauigkeitswert immer abziehen
        $value = rand(1, ceil(($base / 100) * (100 - $chance)));

        return $base - $value;
    }

    public function getOverallArmor()
    {
        if (is_null($this->_overallArmor)) {
            $this->updateArmy();
        }
        return $this->_overallArmor;
    }

    public function setOverallArmor($overallArmor)
    {
        $this->_overallArmor = $overallArmor;
    }

    public function getOverallStrength()
    {
        if (is_null($this->_overallStrength)) {
            $this->updateArmy();
        }
        return $this->_overallStrength;
    }

    public function setOverallStrength($overallStrength)
    {
        $this->_overallStrength = $overallStrength;
        return $this;
    }

}
