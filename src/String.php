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
 * String wrapper class
 *
 * @author Samy Naamani <samy@namani.net>
 * @license https://github.com/sntools/types/blob/master/LICENSE MIT
 */
class String extends Object {
    protected function clear() {
        $this->__setValue('');
    }
    protected function fromString($value) {
        $this->__setValue($value);
        return true;
    }
    protected function fromArray(array $value) {
        return $this->fromString(serialize($value));
    }
    protected function fromBool($value) {
        return $this->fromString(strval($value));
    }
    protected function fromDouble($value) {
        return $this->fromString(strval($value));
    }
    protected function fromInt($value) {
        return $this->fromString(strval($value));
    }
    /**
     * Checks if string is empty
     * @return boolean
     */
    final public function isEmpty() {
        return empty($this->__getValue());
    }
    /**
     * Checks if string matches a regexp
     * @param string $regex Regexp to match the string with
     * @return boolean
     */
    final public function match($regex) {
        Regex::createStrongType($regex);
        return false !== $regex->match($this->__getValue());
    }

    /**
     * Appends a string to this string
     * @param string $string String to add
     * @return self Result string
     */
    public function concat($string) {
        $new = clone $this;
        $new->__setValue($new->__getValue() . $string);
        return $new;
    }

    /**
     * . operator override
     * @param mixed $val
     * @return self
     */
    public function __concat($val) {
        return $this->concat($val);
    }

    public function __toString() {
        return $this->__getValue();
    }

    public function hashCode() {
        return $this->__getValue();
    }
}
