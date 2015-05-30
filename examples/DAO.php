<?php

/*
 * The MIT License
 *
 * Copyright 2015 Darth Killer.
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

namespace SNTools\examples;
use SNTools\Object;

/**
 * Subclasses will have access to the property as a unique PDO connexion
 * shared among them. The connexion cannot be overriden once it's been
 * called once.
 * @property-read \PDO $pdo
 * @author Samy NAAMANI <samy@namani.net>
 * @license https://github.com/sntools/core/blob/master/LICENSE MIT
 */
abstract class DAO extends Object{
    private static $_pdo;
    protected static function __constructStatic() {
        $parent = parent::__constructStatic();
        if(!$parent) {
            self::$_pdo = new \PDO('mysql:host=localhost;dbname=mydb;charset=utf8', 'user', 'pwd', array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
        }
        return $parent;
    }
    public function __get($name) {
        switch($name) {
            case 'pdo':
                return self::$_pdo;
            default:
                return parent::__get($name);
        }
    }
}
