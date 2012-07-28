<?php

class UnderscoreUtilityTest extends PHPUnit_Framework_TestCase {

  public function testIdentity() {
    // from js
    $moe = array('name'=>'moe');
    $moe_obj = (object) $moe;
    $this->assertEquals($moe, ___::identity($moe));
    $this->assertEquals($moe_obj, ___::identity($moe_obj));

    // extra
    $this->assertEquals($moe, __($moe)->identity());
    $this->assertEquals($moe_obj, __($moe_obj)->identity());
    
    // docs
    $moe = array('name'=>'moe');
    $this->assertTrue($moe === ___::identity($moe));
  }

  public function testUniqueId() {
    // docs
    $this->assertEquals(0, ___::uniqueId());
    $this->assertEquals('stooge_1', ___::uniqueId('stooge_'));
    $this->assertEquals(2, ___::uniqueId());
    
    // from js
    $ids = array();
    $i = 0;
    while($i++ < 100) array_push($ids, ___::uniqueId());
    $this->assertEquals(count($ids), count(___::uniq($ids)));

    // extra
    $this->assertEquals('stooges', join('', (___::first(___::uniqueId('stooges'), 7))), 'prefix assignment works');
    $this->assertEquals('stooges', join('', __(__('stooges')->uniqueId())->first(7)), 'prefix assignment works in OO-style call');

    while($i++ < 100) array_push($ids, __()->uniqueId());
    $this->assertEquals(count($ids), count(__()->uniq($ids)));
  }

  public function testTimes() {
    // from js
    $vals = array();
    ___::times(3, function($i) use (&$vals) { $vals[] = $i; });
    $this->assertEquals(array(0,1,2), $vals, 'is 0 indexed');

    $vals = array();
    __(3)->times(function($i) use (&$vals) { $vals[] = $i; });
    $this->assertEquals(array(0,1,2), $vals, 'works as a wrapper in OO-style call');
  
    // docs
    $result = '';
    ___::times(3, function() use (&$result) { $result .= 'a'; });
    $this->assertEquals('aaa', $result);
  }

  public function testMixin() {
    // from js
    ___::mixin(array(
      'myReverse' => function($string) {
        $chars = str_split($string);
        krsort($chars);
        return join('', $chars);
      }
    ));
    $this->assertEquals('aecanap', ___::myReverse('panacea'), 'mixed in a function to _');
    $this->assertEquals('pmahc', __('champ')->myReverse(), 'mixed in a function to _ with OO-style call');
    
    // docs
    ___::mixin(array(
      'capitalize'=> function($string) { return ucwords($string); },
      'yell'      => function($string) { return strtoupper($string); }
    ));
    $this->assertEquals('Moe', ___::capitalize('moe'));
    $this->assertEquals('MOE', ___::yell('moe'));
  }

  public function testTemplate() {
    // from js
    $basicTemplate = ___::template('<%= $thing %> is gettin on my noives!');
    $this->assertEquals("This is gettin on my noives!", $basicTemplate(array('thing'=>'This')), 'can do basic attribute interpolation');
    $this->assertEquals("This is gettin on my noives!", $basicTemplate((object) array('thing'=>'This')), 'can do basic attribute interpolation');

    $backslashTemplate = ___::template('<%= $thing %> is \\ridanculous');
    $this->assertEquals('This is \\ridanculous', $backslashTemplate(array('thing'=>'This')));
    
    $escapeTemplate = ___::template('<%= $a ? "checked=\\"checked\\"" : "" %>');
    $this->assertEquals('checked="checked"', $escapeTemplate(array('a'=>true)), 'can handle slash escapes in interpolations');

    $fancyTemplate = ___::template('<ul><% foreach($people as $key=>$name) { %><li><%= $name %></li><% } %></ul>');
    $result = $fancyTemplate(array('people'=>array('moe'=>'Moe', 'larry'=>'Larry', 'curly'=>'Curly')));
    $this->assertEquals('<ul><li>Moe</li><li>Larry</li><li>Curly</li></ul>', $result, 'can run arbitrary php in templates');

    $namespaceCollisionTemplate = ___::template('<%= $pageCount %> <%= $thumbnails[$pageCount] %> <% ___::each($thumbnails, function($p) { %><div class=\"thumbnail\" rel=\"<%= $p %>\"></div><% }); %>');
    $result = $namespaceCollisionTemplate((object) array(
      'pageCount' => 3,
      'thumbnails'=> array(
        1 => 'p1-thumbnail.gif',
        2 => 'p2-thumbnail.gif',
        3 => 'p3-thumbnail.gif'
      )
    ));
    $expected = '3 p3-thumbnail.gif <div class=\"thumbnail\" rel=\"p1-thumbnail.gif\"></div><div class=\"thumbnail\" rel=\"p2-thumbnail.gif\"></div><div class=\"thumbnail\" rel=\"p3-thumbnail.gif\"></div>';
    $this->assertEquals($expected, $result);

    $noInterpolateTemplate = ___::template("<div><p>Just some text. Hey, I know this is silly but it aids consistency.</p></div>");
    $result = $noInterpolateTemplate();
    $expected = "<div><p>Just some text. Hey, I know this is silly but it aids consistency.</p></div>";
    $this->assertEquals($expected, $result);

    $quoteTemplate = ___::template("It's its, not it's");
    $this->assertEquals("It's its, not it's", $quoteTemplate(new StdClass));

    $quoteInStatementAndBody = ___::template('<%
      if($foo == "bar"){
    %>Statement quotes and \'quotes\'.<% } %>');
    $this->assertEquals("Statement quotes and 'quotes'.", $quoteInStatementAndBody((object) array('foo'=>'bar')));

    $withNewlinesAndTabs = ___::template('This\n\t\tis: <%= $x %>.\n\tok.\nend.');
    $this->assertEquals('This\n\t\tis: that.\n\tok.\nend.', $withNewlinesAndTabs((object) array('x'=>'that')));
    
    $template = ___::template('<i><%- $value %></i>');
    $result = $template((object) array('value'=>'<script>'));
    $this->assertEquals('<i>&lt;script&gt;</i>', $result);

    ___::templateSettings(array(
      'evaluate'    => '/\{\{([\s\S]+?)\}\}/',
      'interpolate' => '/\{\{=([\s\S]+?)\}\}/'
    ));

    $custom = ___::template('<ul>{{ foreach($people as $key=>$name) { }}<li>{{= $people[$key] }}</li>{{ } }}</ul>');
    $result = $custom(array('people'=>array('moe'=>'Moe', 'larry'=>'Larry', 'curly'=>'Curly')));
    $this->assertEquals("<ul><li>Moe</li><li>Larry</li><li>Curly</li></ul>", $result, 'can run arbitrary php in templates using custom tags');

    $customQuote = ___::template("It's its, not it's");
    $this->assertEquals("It's its, not it's", $customQuote(new StdClass));

    $quoteInStatementAndBody = ___::template('{{ if($foo == "bar"){ }}Statement quotes and \'quotes\'.{{ } }}');
    $this->assertEquals("Statement quotes and 'quotes'.", $quoteInStatementAndBody(array('foo'=>'bar')));

    ___::templateSettings(array(
      'evaluate'    => '/<\?([\s\S]+?)\?>/',
      'interpolate' => '/<\?=([\s\S]+?)\?>/'
    ));

    $customWithSpecialChars = ___::template('<ul><? foreach($people as $key=>$name) { ?><li><?= $people[$key] ?></li><? } ?></ul>');
    $result = $customWithSpecialChars(array('people'=>array('moe'=>'Moe', 'larry'=>'Larry', 'curly'=>'Curly')));
    $this->assertEquals("<ul><li>Moe</li><li>Larry</li><li>Curly</li></ul>", $result, 'can run arbitrary php in templates');

    $customWithSpecialCharsQuote = ___::template("It's its, not it's");
    $this->assertEquals("It's its, not it's", $customWithSpecialCharsQuote(new StdClass));

    $quoteInStatementAndBody = ___::template('<? if($foo == "bar"){ ?>Statement quotes and \'quotes\'.<? } ?>');
    $this->assertEquals("Statement quotes and 'quotes'.", $quoteInStatementAndBody(array('foo'=>'bar')));

    ___::templateSettings(array(
      'interpolate' => '/\{\{(.+?)\}\}/'
    ));

    $mustache = ___::template('Hello {{$planet}}!');
    $this->assertEquals("Hello World!", $mustache(array('planet'=>'World')), 'can mimic mustache.js');

    // extra
    ___::templateSettings(); // reset to default
    $basicTemplate = ___::template('<%= $thing %> is gettin\' on my <%= $nerves %>!');
    $this->assertEquals("This is gettin' on my noives!", $basicTemplate(array('thing'=>'This', 'nerves'=>'noives')), 'can do basic attribute interpolation for multiple variables');

    $result = __('hello: <%= $name %>')->template(array('name'=>'moe'));
    $this->assertEquals('hello: moe', $result, 'works with OO-style call');

    $result = __('<%= $thing %> is gettin\' on my <%= $nerves %>!')->template(array('thing'=>'This', 'nerves'=>'noives'));
    $this->assertEquals("This is gettin' on my noives!", $result, 'can do basic attribute interpolation for multiple variables with OO-style call');
  
    $result = __('<%
      if($foo == "bar"){
    %>Statement quotes and \'quotes\'.<% } %>')->template((object) array('foo'=>'bar'));
    $this->assertEquals("Statement quotes and 'quotes'.", $result);
    
    // docs
    $compiled = ___::template('hello: <%= $name %>');
    $result = $compiled(array('name'=>'moe'));
    $this->assertEquals('hello: moe', $result);
    
    $list = '<% ___::each($people, function($name) { %><li><%= $name %></li><% }); %>';
    $result = ___::template($list, array('people'=>array('moe', 'curly', 'larry')));
    $this->assertEquals('<li>moe</li><li>curly</li><li>larry</li>', $result);
    
    ___::templateSettings(array(
      'interpolate' => '/\{\{(.+?)\}\}/'
    ));
    $mustache = ___::template('Hello {{$planet}}!');
    $result = $mustache(array('planet'=>'World'));
    $this->assertEquals('Hello World!', $result);
    
    $template = ___::template('<i><%- $value %></i>');
    $result = $template(array('value'=>'<script>'));
    $this->assertEquals('<i>&lt;script&gt;</i>', $result);
    
    $sans = ___::template('A <% $this %> B');
    $this->assertEquals('A  B', $sans());
  }
  
  public function testEscape() {
    // from js
    $this->assertEquals('Curly &amp; Moe', ___::escape('Curly & Moe'));
    $this->assertEquals('Curly &amp;amp; Moe', ___::escape('Curly &amp; Moe'));
    
    // extra
    $this->assertEquals('Curly &amp; Moe', __('Curly & Moe')->escape());
    $this->assertEquals('Curly &amp;amp; Moe', __('Curly &amp; Moe')->escape());
  }
}