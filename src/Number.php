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
 * Wrapper for numbers (floats and integers)
 * For integer-specific wrapper, see Int and UInt
 *
 * @author Samy Naamani <samy@namani.net>
 * @license https://github.com/sntools/types/blob/master/LICENSE MIT
 */
class Number extends Object implements Comparable {
    protected function fromDouble($value) {
        $this->__setValue($value);
        return true;
    }
    protected function fromInt($value) {
        return $this->fromDouble($value);
    }
    protected function fromBool($value) {
        return $this->fromDouble($value ? 1 : 0);
    }
    protected function fromString($value) {
        $temp = (double)$value;
        return ($temp == $value) ? $this->fromDouble($temp) : false;
    }
    /**
     * Adds a number to this number
     * @param self $b
     * @return self
     */
    public function add($b) {
        static::create($b);
        $new = clone $this;
        $new->__setValue($new->__getValue() + $b->__getValue());
        return $new;
    }
    /**
     * Deduces a number to this number
     * @param self $b
     * @return self
     */
    public function sub($b) {
        static::create($b);
        $new = clone $this;
        $new->__setValue($new->__getValue() - $b->__getValue());
        return $new;
    }
    /**
     * Multiplies a number by this number
     * @param self $b
     * @return self
     */
    public function times($b) {
        static::create($b);
        $new = clone $this;
        $new->__setValue($new->__getValue() * $b->__getValue());
        return $new;
    }
    /**
     * Divides this number using given number
     * @param self $b
     * @return self
     * @throws DivideByZeroException
     */
    public function div($b) {
        static::create($b);
        if($b->equals(0)) throw new DivideByZeroException;
        $new = clone $this;
        $new->__setValue($new->__getValue() / $b->__getValue());
        return $new;
    }
    /**
     * Computes the modulo of this number by given number
     * @param self $b
     * @return self
     * @throws DivideByZeroException
     */
    public function modulo($b) {
        static::create($b);
        if($b->equals(0)) throw new DivideByZeroException;
        $new = clone $this;
        $new->__setValue($new->__getValue() % $b->__getValue());
        return $new;
    }
    
    public function compareTo($b) {
        try {
            static::create($b);
            if($this->__getValue() == $b->__getValue()) $return = 0;
            else $return = ($this->__getValue() < $b->__getValue()) ? -1 : 1;
        }
        catch(\Exception $ex) {
            $ex = null; // IDE anti-warning trick
            $return = 1;
        }
        return $return;
    }
    
    public function greaterThan($b) {
        return 0 < $this->compareTo($b);
    }
    
    public function greaterOrEqual($b) {
        return 0 <= $this->compareTo($b);
    }
    
    public function lessThan($b) {
        return 0 > $this->compareTo($b);
    }
    
    public function lessOrEqual($b) {
        return 0 >= $this->compareTo($b);
    }
    
    public function equals($b) {
        return 0 == $this->compareTo($b);
    }

    protected function clear() {
        $this->__setValue(0);
    }

    public function __is_greater($val) {
        return $this->greaterThan($val);
    }

    public function __is_greater_or_equal($val) {
        return $this->greaterOrEqual($val);
    }

    public function __is_smaller($val) {
        return $this->lessThan($val);
    }

    public function __is_smaller_or_equal($val) {
        return $this->lessOrEqual($val);
    }

    /**
     * + operator override
     * @param mixed $val
     * @return self
     */
    public function __add($val) {
        return $this->add($val);
    }

    /**
     * - operator override
     * @param mixed $val
     * @return self
     */
    public function __sub($val) {
        return $this->sub($val);
    }

    /**
     * * operator override
     * @param mixed $val
     * @return self
     */
    public function __mul($val) {
        return $this->times($val);
    }

    /**
     * / operator override
     * @param mixed $val
     * @return self
     */
    public function __div($val) {
        return $this->div($val);
    }

    /**
     * % operator override
     * @param mixed $val
     * @return self
     */
    public function __mod($val) {
        return $this->module($val);
    }

    /**
     * post -- operator override
     */
    public function __post_dec() {
        return $this->decrement();
    }

    /**
     * post ++ operator override
     */
    public function __post_inc() {
        return $this->increment();
    }

    /**
     * pre -- operator override
     */
    public function __pre_dec() {
        return $this->decrement();
    }

    /**
     * pre ++ operator override
     */
    public function __pre_inc() {
        return $this->increment();
    }

    /**
     * Decreases value by 1
     */
    public function decrement() {
        $this->setValue($this->value - 1);
    }

    /**
     * Increases value by 1
     */
    public function increment() {
        $this->setValue($this->value + 1);
    }
}
