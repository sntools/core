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
 * Wrapper for booleans
 *
 * @author Samy Naamani <samy@namani.net>
 * @license https://github.com/sntools/types/blob/master/LICENSE MIT
 */
class Bool extends Object {
    protected function clear() {
        $this->__setValue(false);
    }
    protected function fromBool($value) {
        $this->__setValue((bool)$value);
        return true;
    }
    protected function fromArray(array $value) {
        return $this->fromBool($value);
    }
    protected function fromDouble($value) {
        return $this->fromBool($value);
    }
    protected function fromInt($value) {
        return $this->fromBool($value);
    }
    protected function fromObject($value) {
        return $this->fromBool($value);
    }
    protected function fromResource($value) {
        return $this->fromBool($value);
    }
    protected function fromString($value) {
        return $this->fromBool($value);
    }

    /**
     * Boolean to integer conversion
     * @return Int
     */
    final public function toInt() {
        $int = null;
        Int::create($int, $this);
        return $int;
    }
}
