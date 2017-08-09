<?php

class VersioningTest extends TestCase
{
    public function testVersion()
    {
        $pretend_version = '5.4.3';

        $this->assertFalse(laravel_version_is('>=', '5.5.0', $pretend_version));
        $this->assertFalse(laravel_version_is('>=', '5.4.4', $pretend_version));
        $this->assertTrue(laravel_version_is('>=', '5.4.3', $pretend_version));
        $this->assertTrue(laravel_version_is('>=', '5.3.0', $pretend_version));

        $this->assertFalse(laravel_version_is('>=', '5.5.1', $pretend_version));
        $this->assertTrue(laravel_version_is('>=', '4.2.4', $pretend_version));
        $this->assertTrue(laravel_version_is('>=', '5.4.10', '5.4.30'));
    }

}
