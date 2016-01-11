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

namespace SNTools\Kernel;

/**
 * Garbage collector static class.
 * Controls how many variables point to a reference (excluding system ones)
 * and removes those references with no variable pointing to them.
 * Triggerred my Memory
 *
 * @author Samy Naamani <samy@namani.net>
 * @license https://github.com/sntools/types/blob/master/LICENSE MIT
 * @ignore
 */
final class GarbageCollector {
    /**
     * Constant used by collect() to know if GC is to be triggered or not
     */
    const CYCLE_MAX = 10000;
    
    /**
     * Linked Memory collection object
     * @var Memory
     */
    private static $memory = null;
    
    /**
     * Links a Memory collection object to the garbage collector
     * @param Memory &$memory
     */
    public static function setMemory(Memory &$memory) {
        self::$memory =& $memory;
    }

    /**
     * Unlinks the Memory collection object
     */
    public static function unsetMemory() {
        self::$memory = null;
    }

    /**
     * Private constructor.
     *
     * Exists to prevent instance creation, since this is a static class.
     */
    private function __construct() {}
    
    /**
     * Count the number of references to the value pointed by given variable.
     * The counting algorithm is based on Alexandre Quercia's work.
     * @link https://github.com/alquerci/php-types-autoboxing/blob/v1.0.0-BETA2/Memory/GarbageCollector.php Alexandre Quercia's Garbage Collector and refCount method
     * @param &mixed $var Variable to count references for
     * @return int
     */
    private static function refCount(&$var) {
        ob_start();
        debug_zval_dump(array(&$var));
        return preg_replace('#^.+refcount\((\d+)\)\s*$#ms', '$1', ob_get_clean()) - 4;
    }
    
    /**
     * Start garbage collection.
     * Will first test if collection should start, using CYCLE_MAX constant.
     * @return int Number of collected variables
     */
    public static function collect() {
        $collected = 0;
        if(!is_null(self::$memory) and (0 == (count(self::$memory) % self::CYCLE_MAX)) and (5 >= rand(0, 100))) {
            foreach(self::$memory as $address => $var) {
                if(self::refCount($var) == 0) {
                    unset(self::$memory[$address]);
                    $collected++;
                }
            }
        }
        return $collected;
    }
}
