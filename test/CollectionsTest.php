<?php

include_once(__DIR__ . '/../underscore.php');

class UnderscoreCollectionsTest extends PHPUnit_Framework_TestCase {
  
  public function testMap() {
    // from js
    $this->assertEquals(array(2,4,6), _::map(array(1,2,3), function($num) {
      return $num * 2;
    }), 'doubled numbers');
    
    $ifnull = _::map(null, function() {});
    $this->assertTrue(is_array($ifnull) && count($ifnull) === 0, 'handles a null property');
    
    // @todo
    /*
    var tripled = _.map([1, 2, 3], function(num){ return num * this.multiplier; }, {multiplier : 3});
    equals(tripled.join(', '), '3, 6, 9', 'tripled numbers with context');
    
    var doubled = _([1, 2, 3]).map(function(num){ return num * 2; });
    equals(doubled.join(', '), '2, 4, 6', 'OO-style doubled numbers');
    
    var ids = _.map(document.body.childNodes, function(n){ return n.id; });
    ok(_.include(ids, 'qunit-header'), 'can use collection methods on NodeLists');
    
    var ids = _.map(document.images, function(n){ return n.id; });
    ok(ids[0] == 'chart_image', 'can use collection methods on HTMLCollections');
    */
  }
  
  public function testDetect() {
    // from js
    $this->assertEquals(2, _::detect(array(1,2,3), function($num) { return $num * 2 === 4; }), 'found the first "2" and broke the loop');
    
    // extra
    $iterator = function($n) { return $n % 2 === 0; };
    $this->assertEquals(2, _::detect(array(1, 2, 3, 4, 5, 6), $iterator));
    $this->assertEquals(false, _::detect(array(1, 3, 5), $iterator));
  }
  
  public function testSelect() {
    $evens = _::select(array(1,2,3,4,5,6), function($num) { return $num % 2 === 0; });
    $this->assertEquals(array(2, 4, 6), $evens, 'selected each even number');
    
    // @todo
    /*
    evens = _.filter([1, 2, 3, 4, 5, 6], function(num){ return num % 2 == 0; });
    equals(evens.join(', '), '2, 4, 6', 'aliased as "filter"');
    */
  }
  
  public function testReject() {
    $odds = _::reject(array(1,2,3,4,5,6), function($num) { return $num % 2 === 0; });
    $this->assertEquals(array(1, 3, 5), $odds, 'rejected each even number');
  }
  
  public function testAll() {
    // from js
    $this->assertTrue(_::all(array(), _::identity()), 'the empty set');
    $this->assertTrue(_::all(array(true, true, true), _::identity()), 'all true values');
    $this->assertFalse(_::all(array(true, false, true), _::identity()), 'one false value');
    $this->assertTrue(_::all(array(0, 10, 28), function($num) { return $num % 2 === 0;  }), 'even numbers');
    $this->assertFalse(_::all(array(0, 11, 28), function($num) { return $num % 2 === 0;  }), 'odd numbers');
    
    // extra
    $this->assertEquals(true, _::all(array()));
    $this->assertEquals(false, _::all(array(null)));
    $this->assertEquals(false, _::all(0));
    $this->assertEquals(false, _::all('0'));
    $this->assertEquals(false, _::all(array(0,1)));
    $this->assertEquals(true, _::all(array(1)));
    $this->assertEquals(true, _::all(array('1')));
    $this->assertEquals(true, _::all(array(1,2,3,4)));
    
    // @todo
    /*
    ok(_.every([true, true, true], _.identity), 'aliased as "every"');
    */
  }
  
  public function testAny() {
    // from js
    $this->assertFalse(_::any(array()), 'the empty set');
    $this->assertFalse(_::any(array(false, false, false)), 'all false values');
    $this->assertTrue(_::any(array(false, false, true)), 'one true value');
    $this->assertFalse(_::any(array(1, 11, 29), function($num) { return $num % 2 === 0; }), 'all odd numbers');
    $this->assertTrue(_::any(array(1, 10, 29), function($num) { return $num % 2 === 0; }), 'an even number');
    
    // extra
    $this->assertFalse(_::any(array()));
    $this->assertFalse(_::any(array(null)));
    $this->assertFalse( _::any(array(0)));
    $this->assertFalse(_::any(array('0')));
    $this->assertTrue(_::any(array(0, 1)));
    $this->assertTrue(_::any(array(1)));
    $this->assertTrue(_::any(array('1')));
    $this->assertTrue(_::any(array(1,2,3,4)));
    
    // @todo
    /*
    ok(_.some([false, false, true]), 'aliased as "some"');
    */
  }
  
  public function testIncludes() {
    // from js
    $this->assertTrue(_::includes(array(1,2,3), 2), 'two is in the array');
    $this->assertFalse(_::includes(array(1,3,9), 2), 'two is not in the array');
    
    // extra
    $collection = array(true, false, 0, 1, -1, 'foo', array(), array('meh'));
    $this->assertTrue(_::includes($collection, true));
    $this->assertTrue(_::includes($collection, false));
    $this->assertTrue(_::includes($collection, 0));
    $this->assertTrue(_::includes($collection, 1));
    $this->assertTrue(_::includes($collection, -1));
    $this->assertTrue(_::includes($collection, 'foo'));
    $this->assertTrue(_::includes($collection, array()));
    $this->assertTrue(_::includes($collection, array('meh')));
    $this->assertFalse(_::includes($collection, 'true'));
    $this->assertFalse(_::includes($collection, '0'));
    $this->assertFalse(_::includes($collection, '1'));
    $this->assertFalse(_::includes($collection, '-1'));
    $this->assertFalse(_::includes($collection, 'bar'));
    $this->assertFalse(_::includes($collection, 'Foo'));
    
    // @todo
    /*
    ok(_.contains({moe:1, larry:3, curly:9}, 3) === true, '_.include on objects checks their values');
    ok(_([1,2,3]).include(2), 'OO-style include');
    */
  }
  
  public function testPluck() {
    // from js
    $people = array(
      array('name'=>'moe', 'age'=>30),
      array('name'=>'curly', 'age'=>50)
    );
    $this->assertEquals(array('moe', 'curly'), _::pluck($people, 'name'), 'pulls names out of objects');
    
    // extra: array
    $stooges = array(
      array('name'=>'moe',   'age'=> 40),
      array('name'=>'larry', 'age'=> 50, 'foo'=>'bar'),
      array('name'=>'curly', 'age'=> 60)
    );
    $this->assertEquals(array('moe', 'larry', 'curly'), _::pluck($stooges, 'name'));
    $this->assertEquals(array(40, 50, 60), _::pluck($stooges, 'age'));
    $this->assertEquals(array('bar'), _::pluck($stooges, 'foo'));
    
    // extra: object
    $stooges_obj = new StdClass;
    foreach($stooges as $stooge) {
      $name = $stooge['name'];
      $stooges_obj->$name = (object) $stooge;
    }
    $this->assertEquals(array('moe', 'larry', 'curly'), _::pluck($stooges, 'name'));
    $this->assertEquals(array(40, 50, 60), _::pluck($stooges, 'age'));
    $this->assertEquals(array('bar'), _::pluck($stooges, 'foo'));
  }
  
  public function testMax() {
    // from js
    $this->assertEquals(3, _::max(array(1,2,3)), 'can perform a regular max');
    $this->assertEquals(1, _::max(array(1,2,3), function($num) { return -$num; }), 'can performa a computation-based max');
    
    // extra
    $stooges = array(
      array('name'=>'moe',   'age'=>40),
      array('name'=>'larry', 'age'=>50),
      array('name'=>'curly', 'age'=>60)
    );
    $this->assertEquals($stooges[2], _::max($stooges, function($stooge) { return $stooge['age']; }));
    $this->assertEquals($stooges[0], _::max($stooges, function($stooge) { return $stooge['name']; }));
  }
  
  public function testMin() {
    // from js
    $this->assertEquals(1, _::min(array(1,2,3)), 'can perform a regular min');
    $this->assertEquals(3, _::min(array(1,2,3), function($num) { return -$num; }), 'can performa a computation-based max');
    
    // extra
    $stooges = array(
      array('name'=>'moe',   'age'=>40),
      array('name'=>'larry', 'age'=>50),
      array('name'=>'curly', 'age'=>60)
    );
    $this->assertEquals($stooges[0], _::min($stooges, function($stooge) { return $stooge['age']; }));
    $this->assertEquals($stooges[2], _::min($stooges, function($stooge) { return $stooge['name']; }));
  }
  
  public function testSortBy() {
    // from js
    $people = array(
      (object) array('name'=>'curly', 'age'=>50),
      (object) array('name'=>'moe', 'age'=>30)
    );
    $people_sorted = _::sortBy($people, function($person) { return $person->age; });
    $this->assertEquals(array('moe', 'curly'), _::pluck($people_sorted, 'name'), 'stooges sorted by age');
    
    // extra
    $stooges = array(
      array('name'=>'moe',   'age'=>40),
      array('name'=>'larry', 'age'=>50),
      array('name'=>'curly', 'age'=>60)
    );
    $this->assertEquals($stooges, _::sortBy($stooges, function($stooge) { return $stooge['age']; }));
    $this->assertEquals(array($stooges[2], $stooges[1], $stooges[0]), _::sortBy($stooges, function($stooge) { return $stooge['name']; }));
    $this->assertEquals(array(5, 4, 6, 3, 1, 2), _::sortBy(array(1, 2, 3, 4, 5, 6), function($num) { return sin($num); }));
  }
}