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

namespace SNTools\Test;
require_once(__DIR__ . '/../vendor/autoload.php');
use SNTools\Object;
use SNTools\PropertyException;

/**
 * @property int $bar
 * @property-read string $foo
 * @author Samy NAAMANI <samy@namani.net>
 * @license https://github.com/sntools/core/blob/master/LICENSE MIT
 */
class A extends Object {
    private $bar = 5;
    public function __get($name) {
        switch ($name) {
            case 'foo':
                return 'fooval';
            case 'bar':
                return $this->bar;
            default:
                return parent::__get($name);
        }
    }
    public function __set($name, $value) {
        switch($name) {
            case 'bar':
                if(is_int($value)) {
                    $this->bar = $value;
                    break;
                }
                else throw new PropertyException("A::bar must be an integer");
            default:
                parent::__set($name, $value);
        }
    }
}