<?php

/*
 * The MIT License
 *
 * Copyright 2015 Samy Naamani.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace SNTools;
use SNTools\Kernel\Memory;
use SNTools\Exception\PropertyException;
use SNTools\Exception\OverrideException;
use SNTools\Exception\InvalidValueException;
use SNTools\Exception\TypeMismatchException;

/**
 * Advanced generic Object class.
 * Setups up default behaviour for various magic methods
 * Superclass for all autoboxed types.
 * This class fills the role of the AutoBoxedObject class, according to the Autoboxing technique from Arthur Graniszewski
 *
 * @author Samy Naamani <samy@namani.net>
 * @license https://github.com/sntools/core/blob/master/LICENSE MIT
 */
class Object {
    /**
     * Real value
     * @var mixed
     */
    private $value;
    /**
     * Memory variable
     * @var int
     */
    private $memoryId;
    /**
     * List of subclasses that has been initiated
     * Used to tell subclasses if they need to run their "static constructor".
     * @var array
     */
    private static $iniClasses = array();

    /**
     * Is the element nullable or not ?
     * @var boolean
     * @see Type::$_nullable_
     */
    private $nullable = false;

    /**
     * Property getters handler.
     * This method is called automatically when trying to read a property.
     * The default behaviour set here is to always throw a PropertyException.
     * Subclasses will want to override this method to add behaviour for their
     * readable properties (example : with switch)
     * @example examples/Person.php
     * @param string $name Property name
     * @return mixed Property value
     * @throws PropertyException
     */
    public function __get($name) {
        $method = "__get_$name";
        if(is_callable(array($this, $method))) return $this->$method();
        throw new PropertyException(sprintf('%s::$%s : invalid property.', $this->getClass(), $name));
    }

    /**
     * Property setters handler.
     * @example examples/Person.php
     * @param string $name Property name
     * @param mixed $value Property value
     * @throws PropertyException
     */
    public function __set($name, $value) {
        $method = "__set_$name";
        if(is_callable(array($this, $method))) return $this->$method($value);
        throw new PropertyException(sprintf('%s::$%s : attempt to write invalid property, using value %s.', get_called_class(), $name, print_r($value, true)));
    }

    /**
     * Property existance checking handler.
     * @param string $name Property name
     * @return boolean Is the propety set ?
     */
    public function __isset($name) {
        $return = true;
        try {
            $ex = $this->$name; // If not set, will throw PropertyException
            // stored in $ex just to trick IDE into thinking $ex is used
        } catch (PropertyException $ex) {
            $return = false;
        }
        return $return;
    }

    /**
     * Property unsetter handler.
     * @param string $name Property name
     * @throws PropertyException
     */
    public function __unset($name) {
        $method = "__unset_$name";
        if(is_callable(array($this, $method))) return $this->$method();
        throw new PropertyException(sprintf('%s::$%s : invalid attempt to delete property.', $this->getClass(), $name));
    }

    final public function __getNullable() {
        return $this->nullable;
    }

    final public function __setNullable($value) {
        $this->nullable = (bool)$value;
    }

    /**
     * Create a new autoboxed variable
     * @param &mixed $var Reference to the variable to create
     * @param mixed|null $value If not null, value to use to override referenced variable
     * @param boolean $override If neither $var nor $value, allow override of $var or not
     * @throws TypeMismatchException
     * @throws InvalidValueException
     * @throws OverrideException
     */
    final public static function createStrongType(&$var, $value = null, $override = false) {
        if(!is_null($var)) {
            if(is_null($value)) $value = $var;
            elseif(!$override) throw new OverrideException;
        }

        if($value instanceof static) {
            $var = clone $value;
        } else {
            $var = new static($value);
        }
        $var->memoryId = Memory::alloc($var);
    }

    /**
     * Static constructor.
     * The default static constructor does nothing besides checking if it has already been loaded.
     * Overrides to add new responsabilities that are to be loaded the first time an instance is created.
     * @example examples/DAO.php
     * @return boolean Has been initialized
     */
    protected static function __constructStatic() {
        $return = in_array(get_called_class(), self::$iniClasses);
        if(!$return) self::$iniClasses[] = get_called_class();
        return $return;
    }

    /**
     * Constructor. Use Type::createStrongType() instead.
     * @param mixed|null $value
     * @throws TypeMismatchException
     * @throws InvalidValueException
     */
    public function __construct($value = null) {
        static::__constructStatic();
        $this->_setValue($value);
    }

    /**
     * Destructor. Frees variable from memory, OR creates a new variable from previous one
     * @ignore
     */
    public function __destruct() {
        if(!is_null($this->memoryId)) {
            $pointer =& Memory::get($this->memoryId);
            $value = $pointer;
            if($value !== $this and !is_null($value)) {
                $pointer = null;
                static::createStrongType($pointer, $value);
            }
            Memory::free($this->memoryId);
        }
    }

    /**
     * Sets value into variable
     * @param mixed $value Value to use
     * @throws TypeMismatchException
     * @throws InvalidValueException
     */
    final protected function __setValue($value) {
        switch(gettype($value)) {
            case 'boolean':
                $ok = $this->fromBool($value);
                break;
            case 'integer':
                $ok = $this->fromInt($value);
                break;
            case 'double':
            case 'string':
            case 'array':
            case 'object':
            case 'resource':
                $method = 'from' . ucfirst(gettype($value));
                $ok = $this->$method($value);
                break;
            case 'NULL':
                if($this->nullable) $this->setValue(null);
                else $this->clear();
                $ok = true;
                break;
            default:
                throw new TypeMismatchException(sprintf('Unexpected type %s for %s', gettype($value), get_called_class ()));
        }
        if(!$ok) throw new InvalidValueException(sprintf('Unexpected value %s for %s', $value, get_called_class ()));
    }
    /**
     * Checks variable creation from boolean
     * @param boolean $value
     * @return boolean
     */
    protected function fromBool() {
        return false;
    }
    /**
     * Check variable creation from integer
     * @param int $value
     * @return boolean
     */
    protected function fromInt() {
        return false;
    }
    /**
     * Check variable creation from double
     * @param double $value
     * @return boolean
     */
    protected function fromDouble() {
        return false;
    }
    /**
     * Check variable creation from string
     * @param string $value
     * @return boolean
     */
    protected function fromString() {
        return false;
    }
    /**
     * Check variable creation from array
     * @param array $value
     * @return boolean
     */
    protected function fromArray() {
        return false;
    }
    /**
     * Check variable creation from object
     * @param object $value
     * @return boolean
     */
    protected function fromObject($value) {
        if($value instanceof self) {
            $this->setValue($value->__getValue());
            $return = true;
        } else $return = false;
        return $return;
    }
    /**
     * Check variable creation from resource
     * @param resource $value
     * @return boolean
     */
    protected function fromResource() {
        return false;
    }
    /**
     * Get inner value
     * @return mixed
     */
    final public function __getValue() {
        return $this->value;
    }

    /**
     * clears inner value, setting it to a default value
     */
    protected function clear() {
        return new \stdClass();
    }

    /**
     * Conversion to boolean
     * @return Bool
     */
    final public function toBool() {
        $bool = null;
        Bool::createStrongType($bool, $this);
        return $bool;
    }

    /**
     * Conversion to string
     * @return String
     */
    final public function toString() {
        $string = null;
        String::createStrongType($string, $this);
        return $string;
    }

    /**
     * (bool) operator overriding
     * @return boolean
     * @todo Query : return a native boolean or a Bool object ?
     */
    public function __bool() {
        return $this->toBool()->__getValue();
    }

    /**
     * ! operator overriding
     * @return boolean
     * @todo Query : return a native boolean or a Bool object ?
     */
    public function __bool_not() {
        return !$this->__bool();
    }

    /**
     * Equality comparision
     * @param mixed $other
     * @return boolean
     */
    public function equals($other) {
        return ($this->__getValue() == (($other instanceof self) ? $other->__getValue() : $other));
    }

    /**
     * Equality comparision
     * @param mixed $other
     * @return boolean
     */
    public function is_identical($other) {
        return ($this->__getValue() === (($other instanceof self) ? $other->__getValue() : $other));
    }

    /**
     * == operator overriding
     * @param mixed $val
     * @return boolean
     * @todo Query : return a native boolean or a Bool object ?
     */
    public function __is_equal($val) {
        return $this->equals($val);
    }

    /**
     * != operator overriding
     * @param mixed $val
     * @return boolean
     * @todo Query : return a native boolean or a Bool object ?
     */
    public function __is_not_equal($val) {
        return !$this->equals($val);
    }

    /**
     * === operator overriding
     * @param mixed $val
     * @return boolean
     */
    public function __is_identical($val) {
        return $this->is_identical($val);
    }

    /**
     * !== operator overriding
     * @param mixed $val
     * @return boolean
     */
    public function __is_not_identical($val) {
        return !$this->is_identical($val);
    }

    /**
     * Cloning handler
     */
    public function __clone() {
        static::createStrongType($this);
    }

    /**
     * Real string conversion handler
     * @return string
     * @todo Query : return native string or String objet ?
     */
    public function __toString() {
        return $this->getClass();
    }

    public function getClass() {
        return get_called_class();
    }

    public function hashCode() {
        return spl_object_hash($this);
    }
}
