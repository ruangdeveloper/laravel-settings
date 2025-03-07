<?php

use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use RuangDeveloper\LaravelSettings\LaravelSettingsServiceProvider;
use RuangDeveloper\LaravelSettings\Services\SettingsService;

#[WithMigration]
#[CoversClass(SettingsService::class)]
class SettingsServiceTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->defineDatabaseMigrations();

        $this->user = new User();
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

    public function testSet()
    {
        $this->app[SettingsService::class]->set('foo', 'bar');

        $this->assertEquals('bar', $this->app[SettingsService::class]->get('foo'));
    }

    public function testSetWithModel()
    {
        $this->app[SettingsService::class]->setWithModel('foo', 'bar', User::class, $this->user->id);

        $this->assertEquals('bar', $this->app[SettingsService::class]->getWithModel('foo', User::class, $this->user->id));
    }

    public function testGet()
    {
        $this->app[SettingsService::class]->set('foo', 'bar');

        $this->assertEquals('bar', $this->app[SettingsService::class]->get('foo'));
    }

    public function testGetWithModel()
    {
        $this->app[SettingsService::class]->setWithModel('foo', 'bar', User::class, $this->user->id);

        $this->assertEquals('bar', $this->app[SettingsService::class]->getWithModel('foo', User::class, $this->user->id));
    }

    public function testDelete()
    {
        $this->app[SettingsService::class]->set('foo', 'bar');
        $this->app[SettingsService::class]->delete('foo');

        $this->assertNull($this->app[SettingsService::class]->get('foo'));
    }

    public function testDeleteWithModel()
    {
        $this->app[SettingsService::class]->setWithModel('foo', 'bar', User::class, $this->user->id);
        $this->app[SettingsService::class]->deleteWithModel('foo', User::class, $this->user->id);

        $this->assertNull($this->app[SettingsService::class]->getWithModel('foo', User::class, $this->user->id));
    }

    public function testTypeCast()
    {
        $this->app[SettingsService::class]->set('integer', 1);
        $this->app[SettingsService::class]->set('float', 1.1);
        $this->app[SettingsService::class]->set('boolean', true);
        $this->app[SettingsService::class]->set('array', ['foo' => 'bar']);
        $this->app[SettingsService::class]->set('object', (object) ['foo' => 'bar']);

        $integerValue = $this->app[SettingsService::class]->getInteger('integer');
        $floatValue = $this->app[SettingsService::class]->getFloat('float');
        $booleanValue = $this->app[SettingsService::class]->getBoolean('boolean');
        $arrayValue = $this->app[SettingsService::class]->getArray('array');
        $objectValue = $this->app[SettingsService::class]->getObject('object');

        $this->assertTrue(is_int($integerValue));
        $this->assertTrue(is_float($floatValue));
        $this->assertTrue(is_bool($booleanValue));
        $this->assertTrue(is_array($arrayValue));
        $this->assertTrue(is_object($objectValue));
    }
}
