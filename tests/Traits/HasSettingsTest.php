<?php

use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\CoversTrait;
use RuangDeveloper\LaravelSettings\LaravelSettingsServiceProvider;
use RuangDeveloper\LaravelSettings\Traits\HasSettings;

#[WithMigration]
#[CoversTrait(HasSettings::class)]
class HasSettingsTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->defineDatabaseMigrations();

        $this->user = new class extends User {
            use HasSettings;

            protected $table = 'users';
        };

        $this->user->name = 'John Doe';
        $this->user->email = 'johndoe@example.com';
        $this->user->password = Hash::make('password');
        $this->user->save();
    }

    protected function getPackageProviders($app)
    {
        return [LaravelSettingsServiceProvider::class];
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    public function testSetSetting()
    {
        $this->user->setSetting('foo', 'bar');

        $this->assertEquals('bar', $this->user->getSetting('foo'));
    }

    public function testGetSetting()
    {
        $this->user->setSetting('foo', 'bar');

        $this->assertEquals('bar', $this->user->getSetting('foo'));
    }

    public function testDeleteSetting()
    {
        $this->user->setSetting('foo', 'bar');
        $this->user->deleteSetting('foo');

        $this->assertNull($this->user->getSetting('foo'));
    }

    public function testTypeCast()
    {
        $this->user->setSetting('integer', 1);
        $this->user->setSetting('float', 1.1);
        $this->user->setSetting('boolean', true);
        $this->user->setSetting('array', ['foo' => 'bar']);
        $this->user->setSetting('object', (object) ['foo' => 'bar']);

        $this->assertTrue(is_int($this->user->getSettingInteger('integer')));
        $this->assertTrue(is_float($this->user->getSettingFloat('float')));
        $this->assertTrue(is_bool($this->user->getSettingBoolean('boolean')));
        $this->assertTrue(is_array($this->user->getSettingArray('array')));
        $this->assertTrue(is_object($this->user->getSettingObject('object')));
    }
}
