<?php

// @see testFunctions()
class FunctionsTestClass {
  const FOO = 'BAR';
  public static $_foo = 'bar';
  public static function methodA() {}
  public static function methodB() {}
  private function _methodC() {}
}

class First {
  public $value = 1;
}

class Second {
  public $value = 1;
}

class UnderscoreObjectsTest extends PHPUnit_Framework_TestCase {
  
  public function testKeys() {
    // from js
    $this->assertEquals(array('one', 'two'), ___::keys((object) array('one'=>1, 'two'=>2)), 'can extract the keys from an object');
    
    $a = array(1=>0);
    $this->assertEquals(array(1), ___::keys($a), 'is not fooled by sparse arrays');
    
    $actual = 'underscore';
    try { $actual = ___::keys(null); } catch(Exception $e) {}
    $this->assertEquals('underscore', $actual, 'throws an exception for null values');
    
    $actual = 'underscore';
    try { $actual = ___::keys(UNDERSCORE_FOO); } catch(Exception $e) {}
    $this->assertEquals('underscore', $actual, 'throws an exception for undefined values');
    
    $actual = 'underscore';
    try { $actual = ___::keys(1); } catch(Exception $e) {}
    $this->assertEquals('underscore', $actual, 'throws an exception for number primitives');
    
    $actual = 'underscore';
    try { $actual = ___::keys('a'); } catch(Exception $e) {}
    $this->assertEquals('underscore', $actual, 'throws an exception for string primitives');
    
    $actual = 'underscore';
    try { $actual = ___::keys(true); } catch(Exception $e) {}
    $this->assertEquals('underscore', $actual, 'throws an exception for boolean primitives');
    
    // extra
    $this->assertEquals(array('one', 'two'), ___::keys(array('one'=>1, 'two'=>2)), 'can extract the keys from an array');
    $this->assertEquals(array('three', 'four'), __(array('three'=>3, 'four'=>4))->keys(), 'can extract the keys from an array using OO-style call');
  
    // docs
    $this->assertEquals(array('name', 'age'), ___::keys((object) array('name'=>'moe', 'age'=>40)));
  }
  
  public function testValues() {
    // from js
    $items = array('one'=>1, 'two'=>2);
    $this->assertEquals(array(1,2), ___::values((object) $items), 'can extract the values from an object');
    
    // extra
    $this->assertEquals(array(1,2), ___::values($items));
    $this->assertEquals(array(1), ___::values(array(1)));
    $this->assertEquals(array(1,2), __($items)->values());
    
    // docs
    $this->assertEquals(array('moe', 40), ___::values((object) array('name'=>'moe', 'age'=>40)));
  }
  
  public function testExtend() {
    // from js
    $result = ___::extend(array(), array('a'=>'b'));
    $this->assertEquals(array('a'=>'b'), $result, 'can extend an array with the attributes of another');
    
    $result = ___::extend((object) array(), (object) array('a'=>'b'));
    $this->assertEquals((object) array('a'=>'b'), $result, 'can extend an object with the attributes of another');
    
    $result = ___::extend(array('a'=>'x'), array('a'=>'b'));
    $this->assertEquals(array('a'=>'b'), $result, 'properties in source override destination');
    
    $result = ___::extend(array('x'=>'x'), array('a'=>'b'));
    $this->assertEquals(array('x'=>'x', 'a'=>'b'), $result, "properties not in source don't get overriden");
    
    $result = ___::extend(array('x'=>'x'), array('a'=>'b'), array('b'=>'b'));
    $this->assertEquals(array('x'=>'x', 'a'=>'b', 'b'=>'b'), $result, 'can extend from multiple sources');
    
    $result = ___::extend(array('x'=>'x'), array('a'=>'a', 'x'=>2), array('a'=>'b'));
    $this->assertEquals(array('x'=>2, 'a'=>'b'), $result, 'extending from multiple source objects last property trumps');
    
    // extra
    $result = __(array('x'=>'x'))->extend(array('a'=>'a', 'x'=>2), array('a'=>'b'));
    $this->assertEquals(array('x'=>2, 'a'=>'b'), $result, 'extending from multiple source objects last property trumps');
    
    // docs
    $expected = (object) array('name'=>'moe', 'age'=>50);
    $result = ___::extend((object) array('name'=>'moe'), (object) array('age'=>50));
    $this->assertEquals($expected, $result);
  }
  
  public function testDefaults() {
    // from js
    $options = array('zero'=>0, 'one'=>1, 'empty'=>'', 'nan'=>acos(8), 'string'=>'string');
    $options = ___::defaults($options, array('zero'=>1, 'one'=>10, 'twenty'=>20));
    $this->assertEquals(0, $options['zero'], 'value exists');
    $this->assertEquals(1, $options['one'], 'value exists');
    $this->assertEquals(20, $options['twenty'], 'default applied');
    
    $options_obj = (object) array('zero'=>0, 'one'=>1, 'empty'=>'', 'nan'=>acos(8), 'string'=>'string');
    $options_obj = ___::defaults($options_obj, (object) array('zero'=>1, 'one'=>10, 'twenty'=>20));
    $this->assertEquals(0, $options_obj->zero, 'value exists');
    $this->assertEquals(1, $options_obj->one, 'value exists');
    $this->assertEquals(20, $options_obj->twenty, 'default applied');
    
    $options = ___::defaults($options, array('empty'=>'full'), array('nan'=>'nan'), array('word'=>'word'), array('word'=>'dog'));
    $this->assertEquals('', $options['empty'], 'value exists');
    $this->assertTrue(___::isNaN($options['nan']), 'NaN is not overridden');
    $this->assertEquals('word', $options['word'], 'new value is added, first one wins');
    
    $options_obj = ___::defaults($options_obj, (object) array('empty'=>'full'), (object) array('nan'=>'nan'), (object) array('word'=>'word'), (object) array('word'=>'dog'));
    $this->assertEquals('', $options_obj->empty, 'value exists');
    $this->assertTrue(___::isNaN($options_obj->nan), 'NaN is not overridden');
    $this->assertEquals('word', $options_obj->word, 'new value is added, first one wins');
  
    // extra
    $options = array('zero'=>0, 'one'=>1, 'empty'=>'', 'nan'=>acos(8), 'string'=>'string');
    $options = __($options)->defaults(array('zero'=>1, 'one'=>10, 'twenty'=>20));
    $this->assertEquals(0, $options['zero'], 'value exists');
    $this->assertEquals(1, $options['one'], 'value exists');
    $this->assertEquals(20, $options['twenty'], 'default applied');
    
    // docs
    $food = (object) array('dairy'=>'cheese');
    $defaults = (object) array('meat'=>'bacon');
    $expected = (object) array('dairy'=>'cheese', 'meat'=>'bacon');
    $this->assertEquals($expected, ___::defaults($food, $defaults));
  }
  
  public function testFunctions() {
    // from js doesn't really apply here because in php function aren't truly first class citizens
    
    // extra
    $this->assertEquals(array('methodA', 'methodB'), ___::functions(new FunctionsTestClass));
    $this->assertEquals(array('methodA', 'methodB'), __(new FunctionsTestClass)->functions());
    $this->assertEquals(array('methodA', 'methodB'), ___::methods(new FunctionsTestClass));
    $this->assertEquals(array('methodA', 'methodB'), __(new FunctionsTestClass)->methods());
  }
  
  public function testClon() {
    // from js
    $moe = array('name'=>'moe', 'lucky'=>array(13, 27, 34));
    $clone = ___::clon($moe);
    $this->assertEquals('moe', $clone['name'], 'the clone as the attributes of the original');
    
    $moe_obj = (object) $moe;
    $clone_obj = ___::clon($moe_obj);
    $this->assertEquals('moe', $clone_obj->name, 'the clone as the attributes of the original');

    $clone['name'] = 'curly';
    $this->assertTrue($clone['name'] === 'curly' && $moe['name'] === 'moe', 'clones can change shallow attributes without affecting the original');
    
    $clone_obj->name = 'curly';
    $this->assertTrue($clone_obj->name === 'curly' && $moe_obj->name === 'moe', 'clones can change shallow attributes without affecting the original');
    
    $clone['lucky'][] = 101;
    $this->assertEquals(101, ___::last($moe['lucky']), 'changes to deep attributes are shared with the original');
    
    $clone_obj->lucky[] = 101;
    $this->assertEquals(101, ___::last($moe_obj->lucky), 'changes to deep attributes are shared with the original');
    
    $val = 1;
    $this->assertEquals(1, ___::clon($val), 'non objects should not be changed by clone');
    
    $val = null;
    $this->assertEquals(null, ___::clon($val), 'non objects should not be changed by clone');
  
    // extra
    $foo = array('name'=>'Foo');
    $bar = __($foo)->clon();
    $this->assertEquals('Foo', $bar['name'], 'works with OO-style call');
    
    // docs
    $stooge = (object) array('name'=>'moe');
    $this->assertEquals((object) array('name'=>'moe'), ___::clon($stooge));
  }
  
  public function testHas() {
    // extra
    $input = array('a'=>1, 'b'=>2, 'c'=>3);
    $this->assertTrue(___::has($input, 'a'));
    $this->assertFalse(___::has($input, 'A'));
    $this->assertFalse(___::has($input, 'ab'));
    $this->assertTrue(___::has((object) $input, 'a'));
    $this->assertFalse(___::has((object) $input, 'A'));
    $this->assertFalse(___::has((object) $input, 'ab'));
    $this->assertTrue(__((object) $input)->has('a'), 'works in OO-style call');
    
    // docs
    $this->assertTrue(___::has($input, 'b'));
  }
  
  public function testIsEqual() {
    // from js
    $moe = (object) array(
      'name' => 'moe',
      'lucky'=> array(13, 27, 34)
    );
    $clone = (object) array(
      'name' => 'moe',
      'lucky'=> array(13, 27, 34)
    );
    $this->assertFalse($moe === $clone, 'basic equality between objects is false');
    $this->assertTrue(___::isEqual($moe, $clone), 'deep equality is true');
    $this->assertTrue(__($moe)->isEqual($clone), 'OO-style deep equality works');
    $this->assertFalse(___::isEqual(5, acos(8)), '5 is not equal to NaN');
    $this->assertTrue(acos(8) != acos(8), 'NaN is not equal to NaN (native equality)');
    $this->assertTrue(acos(8) !== acos(8), 'NaN is not equal to NaN (native identity)');
    $this->assertFalse(___::isEqual(acos(8), acos(8)), 'NaN is not equal to NaN');
    
    if(class_exists('DateTime')) {
      $timezone = new DateTimeZone('America/Denver');
      $this->assertTrue(___::isEqual(new DateTime(null, $timezone), new DateTime(null, $timezone)), 'identical dates are equal');
    }
    
    $this->assertFalse(___::isEqual(null, array(1)), 'a falsy is never equal to a truthy');
    $this->assertEquals(true, __(array('x'=>1, 'y'=>2))->chain()->isEqual(__(array('x'=>1, 'y'=>2))->chain())->value(), 'wrapped objects are equal');
    $getTrue = function() { return true; };
    $this->assertTrue(___::isEqual(array('isEqual'=>$getTrue), array()));
    $this->assertTrue(___::isEqual(array(), array('isEqual'=>$getTrue)));
    
    $this->assertEquals(new First, new First, 'Object instances are equal');
    $this->assertNotEquals(new First, new Second, 'Objects with different constors and identical own properties are not equal');
    $this->assertNotEquals((object) array('value'=>1), new First, 'Object instances and objects sharing equivalent properties are not equal');
    $this->assertNotEquals((object) array('value'=>2), new Second);    
    
    // docs
    $stooge = (object) array('name'=>'moe');
    $clon = ___::clon($stooge);
    $this->assertFalse($stooge === $clon);
    $this->assertTrue(___::isEqual($stooge, $clon));
    
    // @todo Lower memory usage on these
    //$this->assertFalse(___::isEqual(array('x'=>1, 'y'=>null), array('x'=>1, 'z'=>2)), 'objects with the same number of undefined keys are not equal');
    //$this->assertFalse(___::isEqual(___(array('x'=>1, 'y'=>null))->chain(), ___(array('x'=>1, 'z'=>2))->chain()), 'wrapped objects are not equal');
  }
  
  public function testIsEmpty() {
    // from js
    $this->assertFalse(___::isEmpty(array(1)), 'array(1) is not empty');
    $this->assertTrue(___::isEmpty(array()), 'array() is empty');
    $this->assertFalse(___::isEmpty((object) array('one'=>1), '(object) array("one"=>1) is not empty'));
    $this->assertTrue(___::isEmpty(new StdClass), 'new StdClass is empty');
    $this->assertTrue(___::isEmpty(null), 'null is empty');
    $this->assertTrue(___::isEmpty(''), 'the empty string is empty');
    $this->assertFalse(___::isEmpty('moe'), 'but other strings are not');
    
    $obj = (object) array('one'=>1);
    unset($obj->one);
    $this->assertTrue(___::isEmpty($obj), 'deleting all the keys from an object empties it');
  
    // extra
    $this->assertFalse(__(array(1))->isEmpty(), 'array(1) is not empty with OO-style call');
    $this->assertTrue(__(array())->isEmpty(), 'array() is empty with OO-style call');
    $this->assertTrue(__(null)->isEmpty(), 'null is empty with OO-style call');
  
    // docs
    $stooge = (object) array('name'=>'moe');
    $this->assertFalse(___::isEmpty($stooge));
    $this->assertTrue(___::isEmpty(new StdClass));
    $this->assertTrue(___::isEmpty((object) array()));
  }
  
  public function testIsObject() {
    // from js
    $this->assertTrue(___::isObject((object) array(1, 2, 3)));
    $this->assertTrue(___::isObject(function() {}), 'and functions');
    $this->assertFalse(___::isObject(null), 'but not null');
    $this->assertFalse(___::isObject('string'), 'and not string');
    $this->assertFalse(___::isObject(12), 'and not number');
    $this->assertFalse(___::isObject(true), 'and not boolean');
    if(class_exists('DateTimeZone')) {
      $this->assertTrue(___::isObject(new DateTimeZone('America/Denver')), 'objects are');
    }
    
    // extra
    $this->assertTrue(___::isObject(new StdClass), 'empty objects work');
    $this->assertTrue(__(new StdClass)->isObject(), 'works with OO-style call');
    $this->assertFalse(__(2)->isObject());
  }
  
  public function testIsArray() {
    // from js
    $this->assertTrue(___::isArray(array(1,2,3)), 'arrays are');
    
    // extra
    $this->assertFalse(___::isArray(null));
    $this->assertTrue(___::isArray(array()));
    $this->assertTrue(___::isArray(array(array(1,2))));
    $this->assertFalse(__(null)->isArray());
    $this->assertTrue(__(array())->isArray());
    
    // docs
    $this->assertTrue(___::isArray(array(1, 2)));
    $this->assertFalse(___::isArray((object) array(1, 2)));
  }
  
  public function testIsString() {
    // from js
    $this->assertTrue(___::isString(join(', ', array(1,2,3))), 'strings are');
    
    // extra
    $this->assertFalse(___::isString(1));
    $this->assertTrue(___::isString(''));
    $this->assertTrue(___::isString('1'));
    $this->assertFalse(___::isString(array()));
    $this->assertFalse(___::isString(null));
    $this->assertFalse(__(1)->isString());
    $this->assertTrue(__('1')->isString());
    $this->assertTrue(__('')->isString());
    
    // docs
    $this->assertTrue(___::isString('moe'));
    $this->assertTrue(___::isString(''));
  }
  
  public function testIsNumber() {
    // from js
    $this->assertFalse(___::isNumber('string'), 'a string is not a number');
    $this->assertFalse(___::isNumber(null), 'null is not a number');
    $this->assertTrue(___::isNumber(3 * 4 - 7 / 10), 'but numbers are');
    
    // extra
    $this->assertFalse(___::isNumber(acos(8)), 'invalid calculations (nan) are not numbers');
    $this->assertFalse(___::isNumber('1'), 'strings of numbers are not numbers');
    $this->assertFalse(___::isNumber(log(0)), 'infinite values are not numbers');
    $this->assertTrue(___::isNumber(pi()));
    $this->assertTrue(___::isNumber(M_PI));
    $this->assertFalse(__(acos(8))->isNumber());
    $this->assertFalse(__('1')->isNumber());
    $this->assertFalse(__(log(0))->isNumber());
    $this->assertTrue(__(pi())->isNumber());
    $this->assertTrue(__(M_PI)->isNumber());
    $this->assertTrue(__(1)->isNumber());
    
    // docs
    $this->assertTrue(___::isNumber(1));
    $this->assertTrue(___::isNumber(2.5));
    $this->assertFalse(___::isNumber('5'));
  }
  
  public function testIsBoolean() {
    // from js
    $this->assertFalse(___::isBoolean(2), 'a number is not a boolean');
    $this->assertFalse(___::isBoolean('string'), 'a string is not a boolean');
    $this->assertFalse(___::isBoolean('false'), 'the string "false" is not a boolean');
    $this->assertFalse(___::isBoolean('true'), 'the string "true" is not a boolean');
    $this->assertFalse(___::isBoolean(null), 'null is not a boolean');
    $this->assertFalse(___::isBoolean(acos(8)), 'nan values are not booleans');
    $this->assertTrue(___::isBoolean(true), 'but true is');
    $this->assertTrue(___::isBoolean(false), 'and so is false');
    
    // extra
    $this->assertFalse(___::isBoolean(array()));
    $this->assertFalse(___::isBoolean(1));
    $this->assertFalse(___::isBoolean(0));
    $this->assertFalse(___::isBoolean(-1));
    $this->assertFalse(__(array())->isBoolean());
    $this->assertTrue(__(true)->isBoolean());
    $this->assertTrue(__(false)->isBoolean());
    $this->assertFalse(__(0)->isBoolean());
    
    // docs
    $this->assertFalse(___::isBoolean(null));
    $this->assertTrue(___::isBoolean(true));
    $this->assertFalse(___::isBoolean(0));
  }
  
  public function testIsFunction() {
    // from js
    $func = function() {};
    $this->assertFalse(___::isFunction(array(1,2,3)), 'arrays are not functions');
    $this->assertFalse(___::isFunction('moe'), 'strings are not functions');
    $this->assertTrue(___::isFunction($func), 'but functions are');
    
    // extra
    $this->assertFalse(___::isFunction('array_search'), 'strings with names of functions are not functions');
    $this->assertFalse(___::isFunction(new ___));
    $this->assertFalse(__(array(1,2,3))->isFunction());
    $this->assertFalse(__('moe')->isFunction());
    $this->assertTrue(__($func)->isFunction());
    $this->assertFalse(__('array_search')->isFunction());
    $this->assertFalse(__(new ___)->isFunction());
    
    // docs
    $this->assertTrue(___::isFunction(function() {}));
    $this->assertFalse(___::isFunction('trim'));
  }
  
  public function testIsDate() {
    // from js
    $this->assertFalse(___::isDate(1), 'numbers are not dates');
    $this->assertFalse(___::isDate(new StdClass), 'objects are not dates');
    
    if(class_exists('DateTime')) {
      $timezone = new DateTimeZone('America/Denver');
      $this->assertTrue(___::isDate(new DateTime(null, $timezone)), 'but dates are');
    }
    
    // extra
    $this->assertFalse(___::isDate(time()), 'timestamps are not dates');
    $this->assertFalse(___::isDate('Y-m-d H:i:s'), 'date strings are not dates');
    $this->assertFalse(__(time())->isDate());
    
    if(class_exists('DateTime')) {
      $timezone = new DateTimeZone('America/Denver');
      $this->assertTrue(__(new DateTime(null, $timezone))->isDate(), 'dates are dates with OO-style call');
    }
    
    // docs
    $this->assertFalse(___::isDate(null));
    $this->assertFalse(___::isDate('2011-06-09 01:02:03'));
    if(class_exists('DateTime')) {
      $timezone = new DateTimeZone('America/Denver');
      $this->assertTrue(___::isDate(new DateTime(null, $timezone)));
    }
  }
  
  public function testIsNaN() {
    // from js
    $this->assertFalse(___::isNaN(null), 'null not not NaN');
    $this->assertFalse(___::isNaN(0), '0 is not NaN');
    $this->assertTrue(___::isNaN(acos(8)), 'but invalid calculations are');
    
    // extra
    $this->assertFalse(__(null)->isNan(), 'null is not NaN with OO-style call');
    $this->assertFalse(__(0)->isNan(), '0 is not NaN with OO-style call');
    $this->assertTrue(__(acos(8))->isNaN(), 'but invalid calculations are with OO-style call');
  
    // docs
    $this->assertFalse(___::isNaN(null));
    $this->assertTrue(___::isNaN(acos(8)));
  }
  
  public function testTap() {
    // from js
    $intercepted = null;
    $interceptor = function($obj) use (&$intercepted) { $intercepted = $obj; };
    $returned = ___::tap(1, $interceptor);
    $this->assertEquals(1, $intercepted, 'passed tapped object to interceptor');
    $this->assertEquals(1, $returned, 'returns tapped object');
    
    $returned = __(array(1,2,3))->chain()
      ->map(function($n) { return $n * 2; })
      ->max()
      ->tap($interceptor)
      ->value();
    $this->assertTrue($returned === 6 && $intercepted === 6, 'can use tapped objects in a chain');
    
    $returned = ___::chain(array(1,2,3))->map(function($n) { return $n * 2; })
                                       ->max()
                                       ->tap($interceptor)
                                       ->value();
    $this->assertTrue($returned === 6 && $intercepted === 6, 'can use tapped objects in a chain with static call');
  
    // docs
    $interceptor = function($obj) { return $obj * 2; };
    $result = __(array(1, 2, 3))->chain()
      ->max()
      ->tap($interceptor)
      ->value();
    $this->assertEquals(3, $result);
  }
}