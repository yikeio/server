<?php

namespace App\Modules\Common\Actions;

use Mockery\MockInterface;

abstract class Action
{
    protected static array $instances = [];

    public static function run(...$arguments): mixed
    {
        return self::make(static::class)->handle(...$arguments);
    }

    public static function runIf($boolean, ...$arguments)
    {
        return $boolean ? static::run(...$arguments) : null;
    }

    public static function runUnless($boolean, ...$arguments)
    {
        return static::runIf(! $boolean, ...$arguments);
    }

    public static function make(string $action)
    {
        return self::$instances[$action] ??= app($action);
    }

    public static function mock(string $action, ?\Closure $callback = null)
    {
        if (empty(self::$instances[$action]) || ! (self::$instances[$action] instanceof MockInterface)) {
            self::$instances[$action] = new FakeAction(static::class);
        }

        return self::$instances[$action];
    }

    public static function __callStatic(string $method, array $arguments)
    {
        $methods = [
            'spy',
            'partialMock',
            'shouldReceive',
            'assertCalled',
            'assertNotCalled',
            'assertCalledTimes',
            'assertCalledWithClosure',
        ];

        if (in_array($method, $methods)) {
            return self::mock(static::class)->$method(...$arguments);
        }

        throw new \Exception("Method [$method] does not exist.");
    }
}
