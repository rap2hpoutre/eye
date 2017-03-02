<?php

class VersioningTest extends TestCase
{
    public function testGetLaravelVersion()
    {
        $this->assertEquals(5.4, get_laravel_version());
    }
    
    public function testLaravelVersionGreaterThanOrEqualTo()
    {
        $this->assertTrue(laravel_version_greater_than_or_equal_to(5.3));
        $this->assertTrue(laravel_version_greater_than_or_equal_to(5.4));
        $this->assertFalse(laravel_version_greater_than_or_equal_to(5.5));
    }

    public function testLaravelVersionLessThanOrEqualTo()
    {
        $this->assertFalse(laravel_version_less_than_or_equal_to(5.3));
        $this->assertTrue(laravel_version_less_than_or_equal_to(5.4));
        $this->assertTrue(laravel_version_less_than_or_equal_to(5.5));
    }

    public function testLaravelVersionEqualTo()
    {
        $this->assertFalse(laravel_version_equal_to(5.5));
        $this->assertTrue(laravel_version_equal_to(5.4));
        $this->assertFalse(laravel_version_equal_to(5.3));
    }
}
