<?php

/*
 * The MIT License
 *
 * Copyright 2015 Samy NAAMANI.
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