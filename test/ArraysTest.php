<?php

class UnderscoreArraysTest extends PHPUnit_Framework_TestCase {
  
  public function testFirst() {
    // from js
    $this->assertEquals(1, ___::first(array(1,2,3)), 'can pull out the first element of an array');
    $this->assertEquals(array(), ___::first(array(1,2,3), 0), 'can pass an index to first');
    $this->assertEquals(array(1, 2), ___::first(array(1,2,3), 2), 'can pass an index to first');
    $this->assertEquals(1, __(array(1,2,3))->first(), 'can perform OO-style "first()"');    
    
    $result = ___::map(array(array(1,2,3), array(1,2,3)), function($vals) {
      return ___::first($vals);
    });
    $this->assertEquals(array(1,1), $result, 'works well with _.map');
    
    $func = function() { return ___::first(func_get_args()); };
    $result = $func(4,3,2,1);
    $this->assertEquals(4, $result, 'works on an arguments object');
    
    // extra
    $this->assertEquals(array(1), ___::first(array(1,2,3), 1), 'can pass an index of 1 to first');
    $this->assertEquals(array(4,5), __(array(4,5,6,7))->first(2), 'can perform OO-style "first()" with index passed');
  
    $this->assertEquals(1, ___::head(array(1,2,3)), 'aliased as "head"');
    $this->assertEquals(array(), ___::head(array(1,2,3), 0), 'aliased as "head"');
  
    // docs
    $this->assertEquals(5, ___::first(array(5, 4, 3, 2, 1)));
    $this->assertEquals(array(5, 4, 3), ___::first(array(5, 4, 3, 2, 1), 3));
  }
  
  public function testRest() {
    $numbers = array(1,2,3,4);
    
    // from js
    $this->assertEquals(array(2,3,4), ___::rest($numbers), 'working rest()');
    $this->assertEquals(array(1,2,3,4), ___::rest($numbers, 0), 'working rest(0)');
    $this->assertEquals(array(3,4), ___::rest($numbers, 2), 'rest can take an index');
    
    $func = function() { return __(func_get_args())->tail(); };
    $result = $func(1,2,3,4);
    $this->assertEquals(array(2,3,4), $result, 'aliased as tail and works on arguments');
    
    $result = ___::map(array(array(1,2,3), array(1,2,3)), function($vals) { return ___::rest($vals); });
    $this->assertEquals('2,3,2,3', join(',', ___::flatten($result)), 'works well with ___::map');
    
    // extra
    $this->assertEquals(array('b','c'), ___::tail(array('a','b','c')));
    
    // docs
    $this->assertEquals(array(4, 3, 2, 1), ___::rest(array(5, 4, 3, 2, 1)));
  }
  
  public function testInitial() {
    // from js
    $this->assertEquals('1, 2, 3, 4', join(', ', ___::initial(array(1,2,3,4,5))), 'working initial()');
    $this->assertEquals('1, 2', join(', ', ___::initial(array(1,2,3,4), 2)), 'initial can take an index');
    
    $func = function() {
      return __(func_get_args())->initial();
    };
    $result = $func(1,2,3,4);
    $this->assertEquals('1, 2, 3', join(', ', $result), 'initial works on arguments');
    
    $result = ___::map(array(array(1,2,3), array(1,2,3)), function($item) { return ___::initial($item); });
    $this->assertEquals('1,2,1,2', join(',', ___::flatten($result)), 'initial works with map');
    
    // extra
    $this->assertEquals(array('a','b'), __(array('a','b','c'))->initial(), 'works with OO-style calls');
    $this->assertEquals(array(1,2), ___::initial(array(1,2,3)), 'works with no n');
    $this->assertEquals(array(1,2), ___::initial(array(1,2,3), 1), 'works with 1 n');
    $this->assertEquals(array(1), ___::initial(array(1,2,3), 2), 'works with 2 n');
    $this->assertEquals(array(), ___::initial(array(1,2,3), 3), 'works with 3 n');
    $this->assertEquals(array(1), ___::initial(array(1,2,3), 5), 'works with surplus n');
    $this->assertEquals(array(1,2, 3), ___::initial(array(1,2,3), 0), 'works with 0 n');
    $this->assertEquals(array(1,2,3), ___::initial(array(1,2,3), -1), 'works with negative n');
  }
  
  public function testLast() {
    // from js
    $this->assertEquals(3, ___::last(array(1,2,3)), 'can pull out the last element of an array');
    
    $func = function() { return __(func_get_args())->last(); };
    $result = $func(1,2,3,4);
    $this->assertEquals(4, $result, 'works on arguments');
    
    $this->assertEquals('', join(', ', ___::last(array(1,2,3), 0)), 'can pass n to last');
    $this->assertEquals('2, 3', join(', ', ___::last(array(1,2,3), 2)), 'can pass n to last');
    $this->assertEquals('1, 2, 3', join(', ', ___::last(array(1,2,3), 5)), 'can pass n to last');
    
    $result = ___::map(array(array(1,2,3), array(1,2,3)), function($item) { return ___::last($item); });
    $this->assertEquals('3,3', join(',', $result), 'works well with map');
    
    // docs
    $this->assertEquals(1, ___::last(array(5, 4, 3, 2, 1)));
  }
  
  public function testCompact() {
    $vals = array(0, 1, false, 2, false, 3);
    
    // from js
    $this->assertEquals(3, count(___::compact($vals)), 'can trim out all falsy values');
    
    $func = function() { return count(__(func_get_args())->compact()); };
    $result = $func(0, 1, false, 2, false, 3);
    $this->assertEquals(3, $result, 'works on arguments');
    
    // extra
    $this->assertEquals(array(1, 2, 3), ___::compact($vals), 'can remove all falsy values');
    
    // docs
    $this->assertEquals(array(true, 'a', 1), ___::compact(array(false, true, 'a', 0, 1, '')));
  }
  
  public function testFlatten() {
    $list = array(1, array(2), array(3, array(array(array(4)))));
    
    // from js
    $this->assertEquals(array(1,2,3,4), ___::flatten($list), 'can flatten nested arrays');
    $this->assertEquals(___::flatten($list, true), array(1, 2, 3, array(array(array(4)))), 'can shallowly flatten nested arrays');
    
    $func = function() { return ___::flatten(func_get_args()); };
    $result = $func(1, array(2), array(3, array(array(array(4)))));
    $this->assertEquals(array(1,2,3,4), $result, 'works with arguments');
    
    // docs
    $list = array(1, array(2), array(3, array(array(4))));
    $this->assertEquals(array(1, 2, 3, 4), ___::flatten($list));
    $this->assertEquals(array(1, 2, 3, array(array(4))), ___::flatten($list, true));
  }
  
  public function testWithout() {
    $list = array(1, 2, 1, 0, 3, 1, 4);
    
    // from js
    $this->assertEquals(array(1=>2,4=>3,6=>4), ___::without($list, 0, 1), 'can remove all instances of an object');
    $list = array(
      (object) array('one'=>1),
      (object) array('two'=>2)
    );
    $this->assertEquals(2, count(___::without($list, (object) array('one'=>1))), 'uses real object identity for comparisons.');
    $this->assertEquals(1, count(___::without($list, $list[0])), 'ditto.');
    
    $func = function() { return ___::without(func_get_args(), 0, 1); };
    $result = $func(1, 2, 1, 0, 3, 1, 4);
    $this->assertEquals(array(1=>2,4=>3,6=>4), $result, 'works on an arguments object');
    
    $result = ___::union(array(1, 2, 3), array(2, 30, 1), array(1, 40, array(1)));
    $this->assertEquals('1 2 3 30 40 1', join(' ', $result), 'takes the union of a list of nested arrays');
      
    // extra
    $this->assertEquals(array(4,5,6), __(array(4,5,6,7,8))->without(7,8), 'works in OO-style calls');
    
    // docs
    $this->assertEquals(array(5, 4, 4=>1), ___::without(array(5, 4, 3, 2, 1), 3, 2));
  }
  
  public function testUniq() {
    // from js
    $list = array(1, 2, 1, 3, 1, 9);
    $this->assertEquals(array(1, 2, 3, 9), ___::uniq($list), 'can find the unique values of an unsorted array');
    
    $list = array(1, 1, 1, 2, 2, 3);
    $this->assertEquals(array(1, 2, 3), ___::uniq($list), 'can find the unique values of a sorted array faster');
    
    $func = function() { return ___::uniq(func_get_args()); };
    $result = $func(1,2,1,3,1,4);
    $this->assertEquals(array(1,2,3,4), $result, 'works on an arguments object');
    
    $list = array(
      (object) array('name'=>'moe'),
      (object) array('name'=>'curly'),
      (object) array('name'=>'larry'),
      (object) array('name'=>'curly')
    );
    $iterator = function($value) { return $value->name; };
    $this->assertEquals(array('moe', 'curly', 'larry'), ___::map(___::uniq($list, false, $iterator), $iterator), 'can find the unique values of an array using a custom iterator');
    
    $iterator = function($value) { return $value + 1; };
    $list = array(1, 2, 2, 3, 4, 4);
    $this->assertEquals(array(1, 2, 3, 4), ___::uniq($list, true, $iterator), 'iterator works with sorted array');
    
    // extra
    $this->assertEquals(array(4,5,6), __(array(4,5,4,4,5,5,6))->uniq(), 'works with OO call');
    $this->assertEquals(array(4,5,6), __(array(4,5,4,4,5,5,6))->unique(), 'aliased as "unique"');
    
    // docs
    $this->assertEquals(array(2, 4, 1), ___::uniq(array(2, 2, 4, 4, 4, 1, 1, 1)));
  }
  
  public function testIntersection() {
    // from js
    $stooges = array('moe', 'curly', 'larry');
    $leaders = array('moe', 'groucho');
    $this->assertEquals(array('moe'), ___::intersection($stooges, $leaders), 'can take the set intersection of two arrays');
    
    $this->assertEquals(array('moe'), __($stooges)->intersection($leaders), 'can perform an OO-style intersection');
    
    $func = function() use ($leaders) { $args = func_get_args(); return ___::intersection($args[0], $leaders); };
    $result = $func($stooges);
    $this->assertEquals(array('moe'), $result, 'works on an arguments object');
  
    // docs
    $arr1 = array(0, 1, 2, 3);
    $arr2 = array(1, 2, 3, 4);
    $arr3 = array(2, 3, 4, 5);
    $this->assertEquals(array(2, 3), ___::intersection($arr1, $arr2, $arr3));
  }
  
  public function testUnion() {
    // from js
    $result = ___::union(array(1, 2, 3), array(2, 30, 1), array(1, 40));
    $this->assertEquals(array(1, 2, 3, 30, 40), $result, 'takes the union of a list of arrays');
    
    // extra
    $result = __(array(1, 2, 3))->union(array(2, 30, 1), array(1, 40));
    $this->assertEquals(array(1, 2, 3, 30, 40), $result, 'works with OO-style call');
  
    // docs
    $arr1 = array(1, 2, 3);
    $arr2 = array(101, 2, 1, 10);
    $arr3 = array(2, 1);
    $this->assertEquals(array(1, 2, 3, 101, 10), ___::union($arr1, $arr2, $arr3));
  }
  
  public function testDifference() {
    // from js
    $result = ___::difference(array(1, 2, 3), array(2, 30, 40));
    $this->assertEquals(array(1, 3), $result, 'takes the difference of two arrays');
    
    $result = ___::difference(array(1, 2, 3, 4), array(2, 30, 40), array(1, 11, 111));
    $this->assertEquals('3 4', join(' ', $result), 'takes the difference of three arrays');
    
    // extra
    $result = __(array(1, 2, 3))->difference(array(2, 30, 40));
    $this->assertEquals(array(1, 3), $result, 'works with OO-style call');
  
    // docs
    $this->assertEquals(array(1, 3, 4), ___::difference(array(1, 2, 3, 4, 5), array(5, 2, 10)));
  }
  
  public function testZip() {
    // from js
    $names  = array('moe', 'larry', 'curly');
    $ages   = array(30, 40, 50);
    $leaders= array(true);
    $stooges= array(array('moe', 30, true), array('larry', 40, null), array('curly', 50, null));
    $this->assertEquals($stooges, ___::zip($names, $ages, $leaders), 'zipped together arrays of different lengths');
    
    // extra
    $this->assertEquals($stooges, __($names)->zip($ages, $leaders), 'can perform OO-style zips of different length arrays');
    
    $numbers = array(1,2,3);
    $letters = array('a','b','c');
    $expected = array(array(1,'a'), array(2,'b'), array(3,'c'));
    $this->assertEquals($expected, ___::zip($numbers, $letters), 'can perform normal zips');
    $this->assertEquals($expected, __($numbers)->zip($letters), 'can perform OO-style zips');
  
    // docs
    $names = array('moe', 'larry', 'curly');
    $ages = array(30, 40, 50);
    $leaders = array(true, false, false);

    $expected = array(
      array('moe', 30, true),
      array('larry', 40, false),
      array('curly', 50, false)
    );
    $result = ___::zip($names, $ages, $leaders);
    $this->assertEquals($expected, $result);
  }
  
  public function testIndexOf() {
    // from js
    $numbers = array(1,2,3);
    $this->assertEquals(1, ___::indexOf($numbers, 2), 'can compute indexOf');
    $this->assertEquals(-1, ___::indexOf(null, 2), 'handles nulls properly');
    
    $numbers = array(10, 20, 30, 40, 50);
    $this->assertEquals(-1, ___::indexOf($numbers, 35), '35 is not in the list');
    $this->assertEquals(3, ___::indexOf($numbers, 40), '40 is in the list');
    
    $numbers = array(1, 40, 40, 40, 40, 40, 40, 40, 50, 60, 70);
    $this->assertEquals(1, ___::indexOf($numbers, 40), '40 is in the list');
    
    $func = function() { return ___::indexOf(func_get_args(), 2); };
    $result = $func(1,2,3);
    $this->assertEquals(1, $result, 'works on an arguments object');
    
    // extra
    $this->assertEquals(2, __(array('a','b','c','d'))->indexOf('c'), 'works with OO-style calls');
    $this->assertEquals('b', __(array('a'=>5,'b'=>10,'c'=>15))->indexOf(10), 'works with associative arrays');
    $this->assertEquals(1, ___::indexOf('foobar', 'o'), 'works with strings');
  
    // docs
    $this->assertEquals(1, ___::indexOf(array(1, 2, 3, 2, 2), 2));
  }
  
  public function testLastIndexOf() {
    // from js
    $numbers = array(1, 0, 1, 0, 0, 1, 0, 0, 0);
    $this->assertEquals(5, ___::lastIndexOf($numbers, 1), 'can computer lastIndexOf');
    $this->assertEquals(8, ___::lastIndexOf($numbers, 0), 'lastIndexOf the other element');
    $this->assertEquals(-1, ___::lastIndexOf(null, 2), 'handles nulls properly');
    
    $func = function() { return ___::lastIndexOf(func_get_args(), 1); };
    $result = $func(1, 0, 1, 0, 0, 1, 0, 0, 0);
    $this->assertEquals(5, $result, 'works on an arguments object');
    
    // extra
    $this->assertEquals(4, __(array('a','b','c','c','c','d'))->lastIndexOf('c'), 'works with OO-style calls');
    $this->assertEquals('c', __(array('a'=>5,'b'=>10,'c'=>10))->lastIndexOf(10), 'works with associative arrays');
    $this->assertEquals(2, ___::lastIndexOf('foobar', 'o'), 'works with strings');
  
    // docs
    $this->assertEquals(4, ___::lastIndexOf(array(1, 2, 3, 2, 2), 2));
  }
  
  public function testRange() {
    // from js
    $this->assertEquals(array(), ___::range(0), 'range with 0 as a first argument generates an empty array');
    $this->assertEquals(array(0,1,2,3), ___::range(4), 'range with a single positive argument generates an array of elements 0,1,2,...,n-1');
    $this->assertEquals(array(5,6,7), ___::range(5, 8), 'range with two arguments a & b, a<b generates an array of elements a,a+1,a+2,...,b-2,b-1');
    $this->assertEquals(array(), ___::range(8, 5), 'range with two arguments a & b, b<a generates an empty array');
    $this->assertEquals(array(3,6,9), ___::range(3, 10, 3), 'range with three arguments a & b & c, c < b-a, a < b generates an array of elements a,a+c,a+2c,...,b - (multiplier of a) < c');
    $this->assertEquals(array(3), ___::range(3, 10, 15), 'range with three arguments a & b & c, c > b-a, a < b generates an array with a single element, equal to a');
    $this->assertEquals(array(12,10,8), ___::range(12, 7, -2), 'range with three arguments a & b & c, a > b, c < 0 generates an array of elements a,a-c,a-2c and ends with the number not less than b');
    $this->assertEquals(array(0, -1, -2, -3, -4, -5, -6, -7, -8, -9), ___::range(0, -10, -1), 'final example in the Python docs');
  
    // extra
    $this->assertEquals(array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9), ___::range(10));
    $this->assertEquals(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10), ___::range(1, 11));
    $this->assertEquals(array(0, 5, 10, 15, 20, 25), ___::range(0, 30, 5));
    $this->assertEquals(array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9), __(10)->range(), 'works in OO-style calls and 1 parameter');
    $this->assertEquals(array(10,11,12), __(10)->range(13), 'works in OO-style calls and 2 parameters');
    $this->assertEquals(array(3,6,9), __(3)->range(10, 3), 'works in OO-style calls and 3 parameters');
  
    // docs
    $this->assertEquals(array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9), ___::range(10));
    $this->assertEquals(array(1, 2, 3, 4), ___::range(1, 5));
    $this->assertEquals(array(0, 5, 10, 15, 20, 25), ___::range(0, 30, 5));
    $this->assertEquals(array(0, -1, -2, -3, -4), ___::range(0, -5, -1));
    $this->assertEquals(array(), ___::range(0));
  }
}