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
 * Wrapper for integer numbers. For unsigned integers, see UInt
 *
 * @author Samy Naamani <samy@namani.net>
 * @license https://github.com/sntools/types/blob/master/LICENSE MIT
 */
class Int extends Number {

    protected function fromInt($value) {
        $this->__setValue($value);
        return true;
    }

    protected function fromDouble($value) {
        return $this->fromInt((int) $value);
    }

    /**
     * Bitwise AND
     * @param self $b
     * @return self
     */
    public function bw_and($b) {
        static::create($b);
        $new = clone $this;
        $new->__setValue($new->__getValue() & $b->__getValue());
        return $new;
    }

    /**
     * Bitwise OR
     * @param self $b
     * @return self
     */
    public function bw_or($b) {
        static::create($b);
        $new = clone $this;
        $new->__setValue($new->__getValue() | $b->__getValue());
        return $new;
    }

    /**
     * Bitwise XOR
     * @param self $b
     * @return self
     */
    public function bw_xor($b) {
        static::create($b);
        $new = clone $this;
        $new->__setValue($new->__getValue() ^ $b->__getValue());
        return $new;
    }

    /**
     * Bitwise NOT
     * @return self
     */
    public function bw_not() {
        $new = clone $this;
        $new->__setValue(~$new->__getValue());
        return $new;
    }

    /**
     * Bitwise Shift Left
     * @param self $b
     * @return self
     */
    public function bw_shift_left($b) {
        Uint::create($b);
        $new = clone $this;
        $new->__setValue($new->__getValue() << $b->__getValue());
        return $new;
    }

    /**
     * Bitwise Shift Right
     * @param self $b
     * @return self
     */
    public function bw_shift_right($b) {
        UInt::create($b);
        $new = clone $this;
        $new->__setValue($new->__getValue() >> $b->__getValue());
        return $new;
    }

    /**
     * & operator override
     * @param mixed $val
     * @return self
     */
    public function __bw_and($val) {
        return $this->bitwiseAnd($val);
    }

    /**
     * ~ operator override
     * @return boolean
     */
    public function __bw_not() {
        return $this->bitwiseNot();
    }

    /**
     * | operator override
     * @param mixed $val
     * @return self
     */
    public function __bw_or($val) {
        return $this->bitwiseOr($val);
    }

    /**
     * ^ operator override
     * @param mixed $val
     * @return boolean
     */
    public function __bw_xor($val) {
        return $this->bitwiseXor($val);
    }

    /**
     * << operator override
     * @param mixed $val
     * @return self
     */
    public function __sl($val) {
        return $this->slideLeft($val);
    }

    /**
     * >> operator override
     * @param mixed $val
     * @return self
     */
    public function __sr($val) {
        return $this->slideRight($val);
    }
}
