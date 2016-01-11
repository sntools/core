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
 * Enumeration wrapper.
 * Extend this class to add constants to it to create an enumeration
 *
 * @author Samy Naamani <samy@namani.net>
 * @license https://github.com/sntools/types/blob/master/LICENSE MIT
 */
abstract class Enum extends Object {
    /**
     * List of constants in enumeration
     * @return array
     */
    final protected function getConstants() {
        $refl = new \ReflectionClass($this->getClass());
        return array_unique($refl->getConstants());
    }
    protected function clear() {
        $this->value = null;
    }
    /**
     * Checks variable creation from enumeration value
     * @param mixed $value
     * @return boolean
     */
    protected function fromEnumValue($value) {
        if(in_array($value, $this->getConstants(), true)) {
            $this->__setValue($value);
            $return = true;
        } else $return = false;
        return $return;
    }
    final protected function fromArray(array $value) {
        return $this->fromEnumValue($value);
    }
    final protected function fromBool($value) {
        return $this->fromEnumValue($value);
    }
    final protected function fromDouble($value) {
        return $this->fromEnumValue($value);
    }
    final protected function fromInt($value) {
        return $this->fromEnumValue($value);
    }
    final protected function fromObject($value) {
        $ok = parent::fromObject($value);
        return $ok ? true : $this->fromEnumValue($value);
    }
    final protected function fromResource($value) {
        return $this->fromEnumValue($value);
    }
    final protected function fromString($value) {
        return $this->fromEnumValue($value);
    }
    /**
     * Get enumeration name, as string
     * @return string
     * @todo String object instead ?
     */
    public function getConstName() {
        return array_search($this->__getValue(), $this->getConstants());
    }
}
