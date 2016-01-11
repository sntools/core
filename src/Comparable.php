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
 * Interface declaring the comparable feature of some types
 * @author Samy Naamani <samy@namani.net>
 * @license https://github.com/sntools/types/blob/master/LICENSE MIT
 */
interface Comparable {
    /**
     * Compare this element to given element
     * @param mixed $b Element to compare this element to
     * @return int 1 if $b is less than $this, 0 if they are equals, -1 otherwise
     */
    public function compareTo($b);

    /**
     * Checks if number is greater than given number
     * @param mixed $b
     * @return boolean
     */
    public function greaterThan($b);

    /**
     * Checks if number is greater than or equals given number
     * @param mixed $b
     * @return boolean
     */
    public function greaterOrEqual($b);

    /**
     * Checks if number is lesser than given number
     * @param mixed $b
     * @return boolean
     */
    public function lessThan($b);

    /**
     * Checks if number is lesser than or equals given number
     * @param mixed $b
     * @return boolean
     */
    public function lessOrEqual($b);

    /**
     * < Operator override
     * @param mixed $val
     * @return boolean
     */
    public function __is_smaller($val);

    /**
     * <= operator override
     * @param mixed$val
     * @return boolean
     */
    public function __is_smaller_or_equal($val);

    /**
     * > operator override
     * @param boolean $val
     * @return boolean
     */
    public function __is_greater($val);

    /**
     * >= operator override
     * @param boolean $val
     * @return boolean
     */
    public function __is_greater_or_equal($val);
}
