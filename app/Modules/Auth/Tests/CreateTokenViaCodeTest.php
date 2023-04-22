<?php

namespace App\Modules\Auth\Tests;

use App\Modules\Service\State\StateManager;
use Mockery\MockInterface;
use Overtrue\Socialite\Contracts\ProviderInterface;
use Overtrue\Socialite\SocialiteManager;
use Overtrue\Socialite\User;
use Tests\TestCase;

class CreateTokenViaCodeTest extends TestCase
{
    public function test_create_token_via_google_code()
    {
        $this->get('/api/auth/redirect?driver=google')->assertRedirect();

        $this->mock(StateManager::class, function (MockInterface $mock) {
            $mock->shouldReceive('get')->andReturn('google');
        });

        $this->mock(ProviderInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('userFromCode')->andReturn(new User([
                'id' => '123456',
                'nickname' => 'test',
                'name' => 'test',
                'email' => null,
                'avatar' => 'https://avatars.githubusercontent.com/u/123456?v=4',
            ]));
        });

        $this->mock(SocialiteManager::class, function (MockInterface $mock) {
            $mock->shouldReceive('create')->andReturn($this->app->make(ProviderInterface::class));
        });

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('profiles', 0);

        $this->postJson('/api/auth/tokens:via-code', [
            'code' => '123456',
            'state' => sha1('google'),
        ])
            ->assertSuccessful()
            ->assertJsonStructure([
                'value',
                'type',
                'expires_at',
            ]);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('profiles', 1);
    }

    public function test_create_token_via_github_code()
    {
        $this->get('/api/auth/redirect?driver=github')->assertRedirect();

        $this->mock(StateManager::class, function (MockInterface $mock) {
            $mock->shouldReceive('get')->andReturn('github');
        });

        $this->mock(ProviderInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('userFromCode')->andReturn(new User([
                'id' => '123456',
                'nickname' => 'test',
                'name' => 'test',
                'email' => null,
                'avatar' => 'https://avatars.githubusercontent.com/u/123456?v=4',
            ]));
        });

        $this->mock(SocialiteManager::class, function (MockInterface $mock) {
            $mock->shouldReceive('create')->andReturn($this->app->make(ProviderInterface::class));
        });

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('profiles', 0);

        $this->postJson('/api/auth/tokens:via-code', [
            'code' => '123456',
            'state' => sha1('github'),
        ])
            ->assertSuccessful()
            ->assertJsonStructure([
                'value',
                'type',
                'expires_at',
            ]);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('profiles', 1);
    }
}
