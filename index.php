<?php
// Zu beachten ist, dass das php-Tag nicht geschlossen wird. Das hat den Vorteil, dass Leerzeichen nach dem
// schliessenden PHP-Tag nicht ausgegeben werden und so ggf. einen Fehler erzeugen.

// Bootstrap
/**
 * Die index.php Datei ist ein der ersten Dateien die vom Webserver verarbeitet werden. Hier müßen wir ein paar kleinere
 * Hausmeister Tätigkeiten vollziehen. Z.B. sicherstellen das alle Dateien die wir im Laufe der Zeit benötigen auch
 * geladen werden.
 *
 */

/**
 * Namespace dieser Anwendung. Dieses wird verwenden um Kollisionen zwischen unterschiedlichen Programmteilen zu
 * vermeiden. Beispielsweise hat man selbst ein Logging geschrieben aber ein externes Modul bringt auch ein Logger
 * mit, der den gleichen Klassennamen hat. Ein $logger = new Logger(); ist damit nicht mehr eindeutig. Wenn einzelen
 * Module in einem Namespace sizten wird das Risiko einer Kollision reduziert.
 */
namespace oopTutorial;


/**
 * Wenn man saubere Klassenstrukturen aufbaut, kann es sehr schnell zu recht vielen Dateien kommen die zum Start
 * eingebunden werden müßen. Ohne einem Autoloader müsste man jetzt einen Haufen Module mittels require/include
 * einbinden. Das ist erstens sehr unübersichtlich als auch Resourcen fressend. Bei der Verwendung eines Autoloaders
 * werden nur die Klassen instanziert, die auch wirklich gebraucht werden.
 * Der hier verwendete Autoloader ist sehr einfach und ist deshalb als anonyme Funktion (closure) implementiert.
 * Er zerlegt erstmal den Namespace und geht davon aus, dass jedes Element ein Verzeichnis ist, bis auf das letzte
 * Element da wird angenommen das es der Dateiname ist. Somit ergibt sich, dass der Klassenname gleich dem Dateinamen
 * seinen muss. Das schliesst auch groß/klein Schreibung mit ein!
 */
spl_autoload_register(
    function ($className) {
        // Nur Module laden, die zu uns gehören
        if (0 !== strpos($className, __NAMESPACE__)) {
            return false;
        }
        ;
        // Umwanslung des Namensraum in einen tatsächlichen Pfad.
        $path = explode('\\', substr($className, strlen(__NAMESPACE__)));
        $path = '.' . implode('/', $path) . '.php';
        // Überprüfen ob die Datei die geladen werden soll überhaupt existiert und lesbar ist, wenn ncht, dann nicht
        // laden
        if (!is_readable($path)) {
            return false;
        }
        // Datei existiert und kann gelesen werden ,also einlesen.
        require_once $path;
        // Dem Que bescheid geben, dass die gesuchte Klasse gefunden und eingebunden werden konnte.
        return true;
    }
);

/**
 * Wenn irgendwo ein Fehler ausgelöst wird können wir wir diesen hier Systemweit abfangen und
 * kontrolliert im catch-Bereich behandelt.
 */
try {
    /**
     * Neue Armeen anlegen
     *
     */
    $greenArmy = new \oopTutorial\Classes\Army(array(
        'name'      => 'Green',
        'precision' => 20,
        'forces'    => array('soldiers' => buildSoliders(10))
    ));
    $blueArmy  = new \oopTutorial\Classes\Army(array(
        'name'      => 'Blue',
        'precision' => 20,
        'forces'    => array('soldiers' => buildSoliders(10))
    ));
    $pinkArmy  = new \oopTutorial\Classes\Army(array(
        'name'      => 'Pink',
        'precision' => 20,
        'forces'    => array('soldiers' => buildSoliders(10))
    ));


    /**
     *
     */
    $war    = new \oopTutorial\Classes\War(array('armies' => array($greenArmy, $pinkArmy, $blueArmy)));
    $winner = $war->fight();

    echo "The Winner is: " . $winner->getName() . "<br>";

} catch (\Exception $e) {
    echo $e->getMessage();
    echo nl2br($e->getTraceAsString());
}

/**
 * Small helper to generate many soliders at once
 *
 * @param int $numberOfUnits
 * @param int $strength
 * @param int $armor
 * @return array
 */
function buildSoliders($numberOfUnits = 10, $strength = 1, $armor = 1)
{
    $soldiers = array();
    for ($i = 0; $i < $numberOfUnits; $i++) {
        $soldiers[] = new \oopTutorial\Forces\Soldier(array(
            'strength' => $strength,
            'armor'    => $armor,
            'id'       => $i
        ));
    }

    return $soldiers;
}

// Kein schliessender PHP-Tag!
