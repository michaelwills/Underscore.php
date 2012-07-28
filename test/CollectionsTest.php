<?php

class UnderscoreCollectionsTest extends PHPUnit_Framework_TestCase {
  
  public function testEach() {
    // from js
    $test =& $this;
    ___::each(array(1,2,3), function($num, $i) use ($test) {
      $test->assertEquals($num, $i+1, 'each iterators provide value and iteration count');
    });
    
    $answers = array();
    $context = (object) array('multiplier'=>5);
    ___::each(array(1,2,3), function($num) use (&$answers, $context) {
      $answers[] = $num * $context->multiplier;
    });
    $this->assertEquals(array(5,10,15), $answers, 'context object property accessed');
    
    $answers = array();
    $obj = (object) array('one'=>1, 'two'=>2, 'three'=>3);
    ___::each($obj, function($value, $key) use (&$answers) {
      $answers[] = $key;
    });
    $this->assertEquals(array('one','two','three'), $answers, 'iterating over objects works');
    
    $answer = null;
    ___::each(array(1,2,3), function($num, $index, $arr) use (&$answer) {
      if(___::includ($arr, $num)) $answer = true;
    });
    $this->assertTrue($answer, 'can reference the original collection from inside the iterator');
    
    $answers = 0;
    ___::each(null, function() use (&$answers) {
      $answers++;
    });
    $this->assertEquals(0, $answers, 'handles a null property');
    
    // extra
    $test =& $this;
    __(array(1,2,3))->each(function($num, $i) use ($test) {
      $test->assertEquals($num, $i+1, 'each iterators provide value and iteration count within OO-style call');
    });
    
    // docs
    $str = '';
    ___::each(array(1, 2, 3), function($num) use (&$str) { $str .= $num . ','; });
    $this->assertEquals('1,2,3,', $str);

    $str = '';
    $multiplier = 2;
    ___::each(array(1, 2, 3), function($num, $index) use ($multiplier, &$str) {
      $str .= $index . '=' . ($num * $multiplier) . ',';
    });
    $this->assertEquals('0=2,1=4,2=6,', $str);
  }
  
  public function testMap() {
    // from js
    $this->assertEquals(array(2,4,6), ___::map(array(1,2,3), function($num) {
      return $num * 2;
    }), 'doubled numbers');
    
    $ifnull = ___::map(null, function() {});
    $this->assertTrue(is_array($ifnull) && count($ifnull) === 0, 'handles a null property');
    
    $multiplier = 3;
    $func = function($num) use ($multiplier) { return $num * $multiplier; };
    $tripled = ___::map(array(1,2,3), $func);
    $this->assertEquals(array(3,6,9), $tripled);
    
    $doubled = __(array(1,2,3))->map(function($num) { return $num * 2; });
    $this->assertEquals(array(2,4,6), $doubled, 'OO-style doubled numbers');
  
    $this->assertEquals(array(2, 4, 6), ___::map(array(1, 2, 3), function($n) { return $n * 2; }));
    $this->assertEquals(array(2, 4, 6), __(array(1, 2, 3))->map(function($n) { return $n * 2; }));
    
    $doubled = ___::collect(array(1, 2, 3), function($num) { return $num * 2; });
    $this->assertEquals(array(2, 4, 6), $doubled, 'aliased as "collect"');
    
    // docs
    $this->assertEquals(array(3,6,9), ___::map(array(1, 2, 3), function($num) { return $num * 3; }));
    $this->assertEquals(array(3,6,9), ___::map(array('one'=>1, 'two'=>2, 'three'=>3), function($num, $key) { return $num * 3; }));
  }
  
  public function testFind() {
    // from js
    $this->assertEquals(2, ___::find(array(1,2,3), function($num) { return $num * 2 === 4; }), 'found the first "2" and broke the loop');
    
    // extra
    $iterator = function($n) { return $n % 2 === 0; };
    $this->assertEquals(2, ___::find(array(1, 2, 3, 4, 5, 6), $iterator));
    $this->assertEquals(false, ___::find(array(1, 3, 5), $iterator));
    $this->assertEquals(false, __(array(1,3,5))->find($iterator), 'works with OO-style calls');
    $this->assertEquals(___::find(array(1,3,5), $iterator), ___::detect(array(1,3,5), $iterator), 'alias works');
    
    // docs
    $this->assertEquals(2, ___::find(array(1, 2, 3, 4), function($num) { return $num % 2 === 0; }));
  }
  
  public function testFilter() {
    // from js
    $evens = ___::filter(array(1,2,3,4,5,6), function($num) { return $num % 2 === 0; });
    $this->assertEquals(array(2, 4, 6), $evens, 'selected each even number');
    
    // extra
    $odds = __(array(1,2,3,4,5,6))->filter(function($num) { return $num % 2 !== 0; });
    $this->assertEquals(array(1,3,5), $odds, 'works with OO-style calls');
    
    $evens = ___::filter(array(1,2,3,4,5,6), function($num) { return $num % 2 === 0; });
    $this->assertEquals(array(2,4,6), $evens, 'aliased as filter');
    
    $iterator = function($num) { return $num % 2 !== 0; };
    $this->assertEquals(___::filter(array(1,3,5), $iterator), ___::select(array(1,3,5), $iterator), 'alias works');
    
    // docs
    $this->assertEquals(array(2,4), ___::filter(array(1, 2, 3, 4), function($num) { return $num % 2 === 0; }));
  }
  
  public function testReject() {
    // from js
    $odds = ___::reject(array(1,2,3,4,5,6), function($num) { return $num % 2 === 0; });
    $this->assertEquals(array(1, 3, 5), $odds, 'rejected each even number');
    
    // extra
    $evens = __(array(1,2,3,4,5,6))->reject(function($num) { return $num % 2 !== 0; });
    $this->assertEquals(array(2,4,6), $evens, 'works with OO-style calls');
  
    // docs
    $this->assertEquals(array(1, 3), ___::reject(array(1, 2, 3, 4), function($num) { return $num % 2 === 0; }));
  }
  
  public function testAll() {
    // from js
    $this->assertTrue(___::all(array(), ___::identity()), 'the empty set');
    $this->assertTrue(___::all(array(true, true, true), ___::identity()), 'all true values');
    $this->assertFalse(___::all(array(true, false, true), ___::identity()), 'one false value');
    $this->assertTrue(___::all(array(0, 10, 28), function($num) { return $num % 2 === 0;  }), 'even numbers');
    $this->assertFalse(___::all(array(0, 11, 28), function($num) { return $num % 2 === 0;  }), 'odd numbers');
    
    // extra
    $this->assertTrue(___::all(array()));
    $this->assertFalse(___::all(array(null)));
    $this->assertFalse(___::all(0));
    $this->assertFalse(___::all('0'));
    $this->assertFalse(___::all(array(0,1)));
    $this->assertTrue(___::all(array(1)));
    $this->assertTrue(___::all(array('1')));
    $this->assertTrue(___::all(array(1,2,3,4)));
    $this->assertTrue(__(array(1,2,3,4))->all(), 'works with OO-style calls');
    $this->assertTrue(__(array(true, true, true))->all(___::identity()));
    
    $this->assertTrue(__(array(true, true, true))->every(___::identity()), 'aliased as "every"');
  
    // docs
    $this->assertFalse(___::all(array(1, 2, 3, 4), function($num) { return $num % 2 === 0; }));
    $this->assertTrue(___::all(array(1, 2, 3, 4), function($num) { return $num < 5; }));
  }
  
  public function testAny() {
    // from js
    $this->assertFalse(___::any(array()), 'the empty set');
    $this->assertFalse(___::any(array(false, false, false)), 'all false values');
    $this->assertTrue(___::any(array(false, false, true)), 'one true value');
    $this->assertFalse(___::any(array(1, 11, 29), function($num) { return $num % 2 === 0; }), 'all odd numbers');
    $this->assertTrue(___::any(array(1, 10, 29), function($num) { return $num % 2 === 0; }), 'an even number');
    
    // extra
    $this->assertFalse(___::any(array()));
    $this->assertFalse(___::any(array(null)));
    $this->assertFalse( ___::any(array(0)));
    $this->assertFalse(___::any(array('0')));
    $this->assertTrue(___::any(array(0, 1)));
    $this->assertTrue(___::any(array(1)));
    $this->assertTrue(___::any(array('1')));
    $this->assertTrue(___::any(array(1,2,3,4)));
    $this->assertTrue(__(array(1,2,3,4))->any(), 'works with OO-style calls');
    $this->assertFalse(__(array(1,11,29))->any(function($num) { return $num % 2 === 0; }));
    
    $this->assertTrue(___::some(array(false, false, true)), 'alias as "some"');
    $this->assertTrue(__(array(1,2,3,4))->some(), 'aliased as "some"');
  
    // docs
    $this->assertTrue(___::any(array(1, 2, 3, 4), function($num) { return $num % 2 === 0; }));
    $this->assertFalse(___::any(array(1, 2, 3, 4), function($num) { return $num === 5; }));
  }
  
  public function testInclud() {
    // from js
    $this->assertTrue(___::includ(array(1,2,3), 2), 'two is in the array');
    $this->assertFalse(___::includ(array(1,3,9), 2), 'two is not in the array');
    $this->assertTrue(__(array(1,2,3))->includ(2), 'OO-style includ');
    
    // extra
    $collection = array(true, false, 0, 1, -1, 'foo', array(), array('meh'));
    $this->assertTrue(___::includ($collection, true));
    $this->assertTrue(___::includ($collection, false));
    $this->assertTrue(___::includ($collection, 0));
    $this->assertTrue(___::includ($collection, 1));
    $this->assertTrue(___::includ($collection, -1));
    $this->assertTrue(___::includ($collection, 'foo'));
    $this->assertTrue(___::includ($collection, array()));
    $this->assertTrue(___::includ($collection, array('meh')));
    $this->assertFalse(___::includ($collection, 'true'));
    $this->assertFalse(___::includ($collection, '0'));
    $this->assertFalse(___::includ($collection, '1'));
    $this->assertFalse(___::includ($collection, '-1'));
    $this->assertFalse(___::includ($collection, 'bar'));
    $this->assertFalse(___::includ($collection, 'Foo'));
    
    $this->assertTrue(___::contains((object) array('moe'=>1, 'larry'=>3, 'curly'=>9), 3), '___::includ on objects checks their values');
    
    // docs
    $this->assertTrue(___::includ(array(1, 2, 3), 3));
  }
  
  public function testInvoke() {
    // from js
    // the sort example from js doesn't work here because sorting occurs in place in PHP
    $list = array(' foo', ' bar ');
    $this->assertEquals(array('foo','bar'), ___::invoke($list, 'trim'), 'trim applied on array');
    $this->assertEquals((object) array('foo','bar'), ___::invoke((object) $list, 'trim'), 'trim applied on object');
    $this->assertEquals(array('foo','bar'), __($list)->invoke('trim'), 'works with OO-style call');
  
    // docs
    $this->assertEquals(array('foo', 'bar'), ___::invoke(array(' foo', ' bar '), 'trim'));
  }
  
  public function testReduce() {
    // from js
    $sum = ___::reduce(array(1,2,3), function($sum, $num) { return $sum + $num; }, 0);
    $this->assertEquals(6, $sum, 'can sum up an array');
    
    $context = array('multiplier'=>3);
    $sum = ___::reduce(array(1,2,3), function($sum, $num) use ($context) { return $sum + $num * $context['multiplier']; }, 0);
    $this->assertEquals(18, $sum, 'can reduce with a context object');
    
    $sum = ___::reduce(array(1,2,3), function($sum, $num) { return $sum + $num; }, 0);
    $this->assertEquals(6, $sum, 'default initial value');
    
    $ifnull = null;
    try { ___::reduce(null, function() {}); }
    catch(Exception $e) { $ifnull = $e; }
    $this->assertFalse($ifnull === null, 'handles a null (without initial value) properly');
    
    $this->assertEquals(138, ___::reduce(null, function(){}, 138), 'handles a null (with initial value) properly');
    
    $sum = __(array(1,2,3))->reduce(function($sum, $num) { return $sum + $num; });
    $this->assertEquals(6, $sum, 'OO-style reduce');
    
    $sum = ___::inject(array(1,2,3), function($sum, $num) { return $sum + $num; }, 0);
    $this->assertEquals(6, $sum, 'aliased as "inject"');
    
    $sum = ___::foldl(array(1,2,3), function($sum, $num) { return $sum + $num; }, 0);
    $this->assertEquals(6, $sum, 'aliased as "foldl"');
    
    // docs
    $this->assertEquals(6, ___::reduce(array(1, 2, 3), function($memo, $num) { return $memo + $num; }, 0));
  }
  
  public function testReduceRight() {
    // from js
    $list = ___::reduceRight(array('foo', 'bar', 'baz'), function($memo, $str) { return $memo . $str; }, '');
    $this->assertEquals('bazbarfoo', $list, 'can perform right folds');
    
    $ifnull = null;
    try { ___::reduceRight(null, function() {}); }
    catch(Exception $e) { $ifnull = $e; }
    $this->assertFalse($ifnull === null, 'handles a null (without initial value) properly');
    
    $this->assertEquals(138, ___::reduceRight(null, function(){}, 138), 'handles a null (with initial value) properly');
    
    // extra
    $list = __(array('moe','curly','larry'))->reduceRight(function($memo, $str) { return $memo . $str; }, '');
    $this->assertEquals('larrycurlymoe', $list, 'can perform right folds in OO-style');
    
    $list = ___::foldr(array('foo', 'bar', 'baz'), function($memo, $str) { return $memo . $str; }, '');
    $this->assertEquals('bazbarfoo', $list, 'aliased as "foldr"');
    
    $list = ___::foldr(array('foo', 'bar', 'baz'), function($memo, $str) { return $memo . $str; });
    $this->assertEquals('bazbarfoo', $list, 'default initial value');
    
    // docs
    $list = array(array(0, 1), array(2, 3), array(4, 5));
    $flat = ___::reduceRight($list, function($a, $b) { return array_merge($a, $b); }, array());
    $this->assertEquals(array(4, 5, 2, 3, 0, 1), $flat);
  }
  
  public function testPluck() {
    // from js
    $people = array(
      array('name'=>'moe', 'age'=>30),
      array('name'=>'curly', 'age'=>50)
    );
    $this->assertEquals(array('moe', 'curly'), ___::pluck($people, 'name'), 'pulls names out of objects');
    
    // extra: array
    $stooges = array(
      array('name'=>'moe',   'age'=> 40),
      array('name'=>'larry', 'age'=> 50, 'foo'=>'bar'),
      array('name'=>'curly', 'age'=> 60)
    );
    $this->assertEquals(array('moe', 'larry', 'curly'), ___::pluck($stooges, 'name'));
    $this->assertEquals(array(40, 50, 60), ___::pluck($stooges, 'age'));
    $this->assertEquals(array('bar'), ___::pluck($stooges, 'foo'));
    $this->assertEquals(array('bar'), __($stooges)->pluck('foo'), 'works with OO-style call');
    
    // extra: object
    $stooges_obj = new StdClass;
    foreach($stooges as $stooge) {
      $name = $stooge['name'];
      $stooges_obj->$name = (object) $stooge;
    }
    $this->assertEquals(array('moe', 'larry', 'curly'), ___::pluck($stooges, 'name'));
    $this->assertEquals(array(40, 50, 60), ___::pluck($stooges, 'age'));
    $this->assertEquals(array('bar'), ___::pluck($stooges, 'foo'));
    $this->assertEquals(array('bar'), __($stooges)->pluck('foo'), 'works with OO-style call');
  
    // docs
    $stooges = array(
      array('name'=>'moe', 'age'=>40),
      array('name'=>'larry', 'age'=>50),
      array('name'=>'curly', 'age'=>60)
    );
    $this->assertEquals(array('moe', 'larry', 'curly'), ___::pluck($stooges, 'name'));
  }
  
  public function testMax() {
    // from js
    $this->assertEquals(3, ___::max(array(1,2,3)), 'can perform a regular max');
    $this->assertEquals(1, ___::max(array(1,2,3), function($num) { return -$num; }), 'can performa a computation-based max');
    
    // extra
    $stooges = array(
      array('name'=>'moe',   'age'=>40),
      array('name'=>'larry', 'age'=>50),
      array('name'=>'curly', 'age'=>60)
    );
    $this->assertEquals($stooges[2], ___::max($stooges, function($stooge) { return $stooge['age']; }));
    $this->assertEquals($stooges[0], ___::max($stooges, function($stooge) { return $stooge['name']; }));
    $this->assertEquals($stooges[0], __($stooges)->max(function($stooge) { return $stooge['name']; }), 'works with OO-style call');
  
    // docs
    $stooges = array(
      array('name'=>'moe', 'age'=>40),
      array('name'=>'larry', 'age'=>50),
      array('name'=>'curly', 'age'=>60)
    );
    $this->assertEquals(array('name'=>'curly', 'age'=>60), ___::max($stooges, function($stooge) { return $stooge['age']; }));
  }
  
  public function testMin() {
    // from js
    $this->assertEquals(1, ___::min(array(1,2,3)), 'can perform a regular min');
    $this->assertEquals(3, ___::min(array(1,2,3), function($num) { return -$num; }), 'can performa a computation-based max');
    
    // extra
    $stooges = array(
      array('name'=>'moe',   'age'=>40),
      array('name'=>'larry', 'age'=>50),
      array('name'=>'curly', 'age'=>60)
    );
    $this->assertEquals($stooges[0], ___::min($stooges, function($stooge) { return $stooge['age']; }));
    $this->assertEquals($stooges[2], ___::min($stooges, function($stooge) { return $stooge['name']; }));
    $this->assertEquals($stooges[2], __($stooges)->min(function($stooge) { return $stooge['name']; }), 'works with OO-style call');
  
    // docs
    $stooges = array(
      array('name'=>'moe', 'age'=>40),
      array('name'=>'larry', 'age'=>50),
      array('name'=>'curly', 'age'=>60)
    );
    $this->assertEquals(array('name'=>'moe', 'age'=>40), ___::min($stooges, function($stooge) { return $stooge['age']; }));
  }
  
  public function testSortBy() {
    // from js
    $people = array(
      (object) array('name'=>'curly', 'age'=>50),
      (object) array('name'=>'moe', 'age'=>30)
    );
    $people_sorted = ___::sortBy($people, function($person) { return $person->age; });
    $this->assertEquals(array('moe', 'curly'), ___::pluck($people_sorted, 'name'), 'stooges sorted by age');
    
    // extra
    $stooges = array(
      array('name'=>'moe',   'age'=>40),
      array('name'=>'larry', 'age'=>50),
      array('name'=>'curly', 'age'=>60)
    );
    $this->assertEquals($stooges, ___::sortBy($stooges, function($stooge) { return $stooge['age']; }));
    $this->assertEquals(array($stooges[2], $stooges[1], $stooges[0]), ___::sortBy($stooges, function($stooge) { return $stooge['name']; }));
    $this->assertEquals(array(5, 4, 6, 3, 1, 2), ___::sortBy(array(1, 2, 3, 4, 5, 6), function($num) { return sin($num); }));
    $this->assertEquals($stooges, __($stooges)->sortBy(function($stooge) { return $stooge['age']; }), 'works with OO-style call');
  
    // docs
    $this->assertEquals(array(3, 2, 1), ___::sortBy(array(1, 2, 3), function($n) { return -$n; }));
  }
  
  public function testGroupBy() {
    // from js
    $parity = ___::groupBy(array(1,2,3,4,5,6), function($num) { return $num % 2; });
    $this->assertEquals(array(array(2,4,6), array(1,3,5)), $parity, 'created a group for each value');
      
    // extra
    $parity = __(array(1,2,3,4,5,6))->groupBy(function($num) { return $num % 2; });
    $this->assertEquals(array(array(2,4,6), array(1,3,5)), $parity, 'created a group for each value using OO-style call');
    
    $vals = array(
      array('name'=>'rejected', 'yesno'=>'no'),
      array('name'=>'accepted', 'yesno'=>'yes'),
      array('name'=>'allowed', 'yesno'=>'yes'),
      array('name'=>'denied', 'yesno'=>'no')
    );
    $grouped = ___::groupBy($vals, 'yesno');
    $this->assertEquals('rejected denied', join(' ', ___::pluck($grouped['no'], 'name')), 'pulls no entries');
    $this->assertEquals('accepted allowed', join(' ', ___::pluck($grouped['yes'], 'name')), 'pulls yes entries');
    
    // docs
    $result = ___::groupBy(array(1, 2, 3, 4, 5), function($n) { return $n % 2; });
    $this->assertEquals(array(0=>array(2, 4), 1=>array(1, 3, 5)), $result);
    
    $values = array(
      array('name'=>'Apple',   'grp'=>'a'),
      array('name'=>'Bacon',   'grp'=>'b'),
      array('name'=>'Avocado', 'grp'=>'a')
    );
    $expected = array(
      'a'=>array(
        array('name'=>'Apple',   'grp'=>'a'),
        array('name'=>'Avocado', 'grp'=>'a')
      ),
      'b'=>array(
        array('name'=>'Bacon',   'grp'=>'b')
      )
    );
    $this->assertEquals($expected, ___::groupBy($values, 'grp'));
  }
  
  public function testSortedIndex() {
    // from js
    $numbers = array(10, 20, 30, 40, 50);
    $num = 35;
    $index = ___::sortedIndex($numbers, $num);
    $this->assertEquals(3, $index, '35 should be inserted at index 3');
    
    // extra
    $this->assertEquals(3, __($numbers)->sortedIndex(35), '35 should be inserted at index 3 with OO-style call');
  
    // docs
    $this->assertEquals(3, ___::sortedIndex(array(10, 20, 30, 40), 35));
  }
  
  public function testShuffle() {
    // from js
    $numbers = ___::range(10);
    $shuffled = ___::shuffle($numbers);
    sort($shuffled);
    
    $this->assertEquals(join(',', $numbers), join(',', $shuffled), 'contains the same members before and after shuffle');
  }
  
  public function testToArray() {
    // from js
    $numbers = ___::toArray((object) array('one'=>1, 'two'=>2, 'three'=>3));
    $this->assertEquals('1, 2, 3', join(', ', $numbers), 'object flattened into array');
    
    // docs
    $stooge = new StdClass;
    $stooge->name = 'moe';
    $stooge->age = 40;
    $this->assertEquals(array('name'=>'moe', 'age'=>40), ___::toArray($stooge));
  }
  
  public function testSize() {
    // from js
    $items = (object) array(
      'one'   =>1,
      'two'   =>2,
      'three' =>3
    );
    $this->assertEquals(3, ___::size($items), 'can compute the size of an object');
    
    // extra
    $this->assertEquals(0, ___::size(array()));
    $this->assertEquals(1, ___::size(array(1)));
    $this->assertEquals(3, ___::size(array(1, 2, 3)));
    $this->assertEquals(6, ___::size(array(null, false, array(), array(1,2,array('a','b')), 1, 2)));
    $this->assertEquals(3, __(array(1,2,3))->size(), 'works with OO-style calls');
  
    // docs
    $stooge = new StdClass;
    $stooge->name = 'moe';
    $stooge->age = 40;
    $this->assertEquals(2, ___::size($stooge));
  }
}