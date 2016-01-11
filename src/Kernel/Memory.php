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
use SNTools\Exception\OutOfMemoryException;

/**
 * Memory collection of referenced variables.
 * This class fills the role of variable manager, according to the Autoboxing technique from Arthur Graniszewski
 *
 * @link http://www.phpclasses.org/package/6570-PHP-Wrap-string-and-integer-values-in-objects.html Arthur Graniszewski's Autoboxing classes
 *
 * @author Samy Naamani <samy@namani.net>
 * @license https://github.com/sntools/types/blob/master/LICENSE MIT
 * @ignore
 */
final class Memory implements \IteratorAggregate, \Countable, \ArrayAccess{
    /**
     * The actual variables collection
     * @var &mixed[]
     */
    private $pointers = array();
    
    /**
     * Last variable address in the collection.
     * Special value "-1" means "no address yet".
     * @var int
     */
    private static $lastAddress = -1;

    /**
     * Private constructor. Only static methods can create instances.
     * The instance will register itself into the garbage collector upon construction.
     */
    private function __construct() {
        GarbageCollector::setMemory(&$this);
    }

    /**
     * Counts number of variables in the collection
     * @see \Countable::count()
     * @return int
     */
    public function count() {
        return count($this->pointers);
    }

    /**
     * Returns iterator for list of pointers in memory
     * @see \IteratorAggregate::getIterator()
     * @return \ArrayIterator
     */
    public function getIterator() {
        return new \ArrayIterator($this->pointers);
    }

    /**
     * Checks if element exists
     * @see \ArrayAccess::offsetExists()
     * @param int|string $offset Key for element
     * @return boolean
     */
    public function offsetExists($offset) {
        return isset($this->pointers[$offset]);
    }

    /**
     * Get element. Returns null if none found.
     * @see \ArrayAccess::offsetGet()
     * @param int|string $offset Key for element
     * @return mixed|null
     */
    public function offsetGet($offset) {
        return $this->offsetExists($offset) ? $this->pointers[$offset] : null;
    }

    /**
     * Sets element into collection
     * @see \ArrayAccess::offsetSet()
     * @param int|string $offset Key for element
     * @param &mixed $value Element. Here, the element is added as reference into the collection.
     */
    public function offsetSet($offset, &$value) {
        $this->pointers[$offset] =& $value;
    }

    /**
     * Unsets element in collection
     * @see \ArrayAccess::offsetUnset()
     * @param int|string $offset Key for element
     */
    public function offsetUnset($offset) {
        unset($this->pointers[$offset]);
    }
    
    /**
     * Creates instance of Memory, as private singleton
     * @staticvar self $instance Singleton instance
     * @return self
     */
    private static function getInstance() {
        static $instance = null;
        if(is_null($instance)) $instance = new self();
        return $instance;
    }
    
    /**
     * Allocates a new variable into memory.
     * @param &mixed $var Variable reference to count.
     * @return int Variable address in the memory collection
     * @throws OutOfMemoryException
     */
    public static function alloc(&$var) {
        $memory = self::getInstance();
        GarbageCollector::collect(); // GC checks cycles to know if it has to actually collect or not
        if(self::$lastAddress < PHP_INT_MAX) {
            $address = ++self::$lastAddress;
        }
        elseif(count($memory) == PHP_INT_MAX) throw new OutOfMemoryException;
        else {
            do {
                $address = rand(0, PHP_INT_MAX - 1);
            }while(isset($memory[$address]));
        }
        $memory[$address] =& $var;
        return $address;
    }

    /**
     * Gets a variable from memory
     * @param int $id Variable address
     * @return &mixed|null Variable, as reference. Null if not found.
     */
    public static function &get($id) {
        $memory = self::getInstance();
        return $memory[$id];
    }

    /**
     * Frees a variable from memory
     * @param int $id Variable address
     */
    public static function free($id) {
        $memory = self::getInstance();
        unset($memory[$id]);
    }
}
