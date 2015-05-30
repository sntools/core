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
//require_once(__DIR__ . '/bootstrap.php');

use SNTools\PropertyException;

/**
 * @author Samy NAAMANI <samy@namani.net>
 * @license https://github.com/sntools/core/blob/master/LICENSE MIT
 */
class ObjectTest extends \PHPUnit_Framework_TestCase {
    /**
     * @test
     */
    public function isseters() {
        $a = new A();
        $this->assertTrue(isset($a->foo));
        $this->assertTrue(isset($a->bar));
        $this->assertFalse(isset($a->misc));
    }

    /**
     * @test
     * @depends isseters
     */
    public function getters() {
        $a = new A();
        $this->assertEquals('fooval', $a->foo);
        $this->assertEquals(5, $a->bar);
        try {
            $ex = $a->misc;
            $this->fail('Should not have been able to access $a->misc');
        } catch (PropertyException $ex) {
            $this->assertTrue(true);
        }
    }

    /**
     * @test
     * @depends getters
     */
    public function setters() {
        $a = new A();
        $a->bar = 10;
        $this->assertEquals(10, $a->bar);
        try {
            $a->bar = 'machin';
            $this->fail('Should not have been able to set a string into $a->bar');
        } catch (PropertyException $ex) {
            $this->assertTrue(true);
        }
        try {
            $a->foo = 'bar';
            $this->fail('Should not have been able to modify $a->foo');
        } catch (PropertyException $ex) {
            $this->assertTrue(true);
        }
    }
}