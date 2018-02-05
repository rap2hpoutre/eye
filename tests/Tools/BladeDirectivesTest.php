<?php

namespace Eyewitness\Eye\Test\Tools;

use Mockery;
use Eyewitness\Eye\Test\TestCase;
use Eyewitness\Eye\Tools\BladeDirectives;

class BladeDirectivesTest extends TestCase
{
    protected $blade;

    public function setUp()
    {
        parent::setUp();

        $this->blade = Mockery::mock('\Eyewitness\Eye\Tools\BladeDirectives[loadFile]');
    }

    public function test_generates_icon_string_with_defaults()
    {
        $this->blade->shouldReceive('loadFile')->once()->andReturn('<svg width="32" height="32"></svg>');

        $string = $this->blade->getIconString('example');

        $this->assertContains('<svg', $string);
        $this->assertContains('width="32" height="32"', $string);
        $this->assertContains('class=""', $string);
    }

    public function test_generates_icon_string_with_overrides()
    {
        $this->blade->shouldReceive('loadFile')->once()->andReturn('<svg width="32" height="32"></svg>');

        $string = $this->blade->getIconString('example', 'test', 16, 48);

        $this->assertContains('<svg', $string);
        $this->assertContains('width="16" height="48"', $string);
        $this->assertContains('class="test"', $string);
    }
}
