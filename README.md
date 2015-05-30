# SN Toolbox : Core Tools

The SN Toolbox aims to provide classic tools in PHP development that will often be needed, without having to implement them over and over.

This particular package brings the Core Tools : tools that will come in handy in most project, as well as required components for other tools of the Toolbox.

# Download

The easiest way to download the core tools is through Composer. Simply add the following to your composer requirements, where "dev-master" can be replaced by any version you need :

```
"sntools/core": "dev-master"
```

# The NotImplementedException special exception type

A rather unique type of exception, the SNTools\NotImplementedException class is meant to point out
that the current feature has not been implemented yet. Therefore, it is not supposed to appear in the final released code.

This exception is most useful when you know the signature of a method, but not yet how to implement it. You can
that way start working on other components that will require said method, or let your teammates work on it.

# The Object general superclass

All full-object languages, like Java and C#, feature an Object class that is superclass to all others. The SNTools\Object class is intended to work the same way.

This class provides with default behaviour for various magic methods, most notably the __toString() method for string conversion, and the various methods for property handling.

Property handlers rely on SNTools\PropertyException exceptions for error handling (e.g. attempts to write a readonly property)

The Object class also provides with a default "static constructor". This special non-public method is automatically called the first time an object of the class is created.
This is useful for classes that need to be initialized before building new instances.

# Usage example of Object : Person class and property handlers

```php
<?php
namespace SNTools\examples;
use SNTools\Object;
use SNTools\PropertyException;

/**
 * @property string $name
 * @property \DateTime $birthday
 * @property-read int $age
 * @author Samy NAAMANI <samy@namani.net>
 * @license https://github.com/sntools/core/blob/master/LICENSE MIT
 */
class Person extends Object {
    /** @var string */
    private $_name;
    /** @var \DateTime */
    private $_birthday;

    public function __construct($name, \DateTime $birthday = null) {
        parent::__construct();
        $this->name = $name;
        $this->birthday = is_null($birthday) ? new \DateTime() : $birthday;
    }

    public function __get($name) {
        switch($name) {
            case 'name':
            case 'birthday':
                $prop = "_$name";
                return $this->$prop;
            case 'age':
                return $this->birthday->diff(new \DateTime, true)->y;
            default:
                return parent::__get($name);
        }
    }

    public function __set($name, $value) {
        switch($name) {
            case 'name':
                if(!is_string($value)) throw new PropertyException('A person\'s name must be a string');
                if(empty($value)) throw new PropertyException('A person\'s name cannot be empty');
                $this->_name = $value;
                break;
            case 'birthday':
                if(interface_exists('\\DateTimeInterface') and $value instanceof \DateTimeInterface or $value instanceof \DateTime) {
                    if($value->diff(new \DateTime)->invert) throw new PropertyException('Nobody can be born in the future');
                    $this->_birthday = $value;
                } else throw new PropertyException('A person\'s birthday must be a date-time value');
                break;
            default:
                parent::__set($name, $value);
        }
    }
}

/// Usage
$paul = new Person('Paul', new \DateTime("5 years ago"));
printf("This line should display 'Paul' : %s\n", $paul->name);
$paul->name = 'Paul Edward';
printf("This line should display 'Paul Edward' : %s\n", $paul->name);
printf("This line should display a date from 5 years ago as MONTH-DAY-YEAR : %s\n", $paul->birthday->format('m-d-Y'));
printf("This line should display '5' : %s\n", $paul->age);
try {
    $paul->age = 6; // this will throw an exception
} catch (PropertyException $ex) {
    printf("Trying to write on age (not writable) caused exception : %s\n", $ex->getMessage());
}
```

# Usage example : DAO abstract class and static constructor

```php
<?php
namespace SNTools\examples;
use SNTools\Object;

/**
 * Subclasses will have access to the property as a unique PDO connexion
 * shared among them. The connexion cannot be overriden once it's been
 * called once.
 * @property-read \PDO $pdo
 * @author Samy NAAMANI <samy@namani.net>
 * @license https://github.com/sntools/core/blob/master/LICENSE MIT
 */
abstract class DAO extends Object{
    private static $_pdo;
    protected static function __constructStatic() {
        $parent = parent::__constructStatic();
        if(!$parent) {
            self::$_pdo = new \PDO('mysql:host=localhost;dbname=mydb;charset=utf8', 'user', 'pwd', array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
        }
        return $parent;
    }
    public function __get($name) {
        switch($name) {
            case 'pdo':
                return self::$_pdo;
            default:
                return parent::__get($name);
        }
    }
}
```

# API Reference

More detailed documentation is avilable as HTML files, in the docs/ subfolder.

# Testing

Unit tests have been provided, using PHPUnit, in the tests/ subfolder.

# Contributors

Samy NAAMANI <samy@namani.net>

# License

MIT