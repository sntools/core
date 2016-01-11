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
 * @author Samy Naamani
 * @license https://github.com/sntools/types/blob/master/LICENSE MIT
 */
abstract class Flag extends Enum {
    /**
     * @return int
     */
    private function getCombinedflags() {
        $result = 0;
        foreach($this->getConstants() as $constant) {
            $result |= $constant;
        }
        return $result;
    }
    protected function fromEnumValue($value) {
        UInt::create($value);
        /* @var $value UInt */
        if($value->getValue() & $this->getCombinedflags()) $return = false;
        else {
            $this->__setValue($value->__getValue());
            $return = true;
        }
        return $return;
    }
    /**
     * Get flag constant name, as an array. If more than one flag are combined, list combined flag
     * @return array
     */
    public function getConstName() {
        $result = array();
        foreach($this->getConstants() as $name => $value) {
            if($this->__getValue() & $value) $result[] = $name;
        }
        return $result;
    }

    /**
     * Checks
     * @param int $val
     * @return boolean
     */
    public function hasFlag($val) {
        Int::create($val);
        return (bool)($val->bw_and($this)->__getValue());
    }

    /**
     *
     * @param int $val
     */
    public function removeFlag($val) {
        Int::create($val);
        $this->__setValue($val->bw_not()->bw_and($this));
    }

    /**
     *
     * @param int $val
     */
    public function addFlag($val) {
        $this->__setValue($val->bw_or($this));
    }
}
