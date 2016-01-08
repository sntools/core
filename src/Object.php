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

/**
 * Advanced generic Object class.
 * Setups up default behaviour for various magic methods
 *
 * @author Samy Naamani <samy@namani.net>
 * @license https://github.com/sntools/core/blob/master/LICENSE MIT
 */
class Object {

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
        if(is_callable([$this, $method])) return $this->$method();
        throw new PropertyException(sprintf('%s::$%s : invalid property.', get_called_class(), $name));
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
        if(is_callable([$this, $method])) return $this->$method($value);
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
        if(is_callable([$this, $method])) return $this->$method();
        throw new PropertyException(sprintf('%s::$%s : invalid attempt to delete property.', get_called_class(), $name));
    }

    /**
     * Object to string conversion handler.
     * @return string
     */
    public function __toString() {
        return get_called_class();
    }

    /**
     * Object constructor.
     */
    public function __construct() {
        static::__constructStatic();
    }

    /**
     * Static constructor. 
     * The default static constructor does nothing besides checking if it has already been loaded.
     * Overrides to add new responsabilities that are to be loaded the first time an instance is created.
     * @example examples/DAO.php
     * @staticvar boolean $init Has been initialized.
     * @return boolean Has been initialized
     */
    protected static function __constructStatic() {
        static $init = false;
        $return = $init;
        if (!$init) $init = true;
        return $return;
    }

}
