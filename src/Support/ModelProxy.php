<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Traits\ForwardsCalls;
use ReflectionMethod;

class ModelProxy
{
    use ForwardsCalls;

    protected string $model_class;

    protected static array $staticMethodsCache = [];

    public function __construct(string $model_class)
    {
        $this->model_class = $model_class;
    }

    public function class(): string
    {
        return $this->model_class;
    }

    public function query(): Builder
    {
        return $this->model_class::query();
    }

    public function __call(string $method, array $parameters)
    {
        if ($this->isStaticMethod($method)) {
            return $this->model_class::$method(...$parameters);
        }

        return $this->forwardCallTo($this->query(), $method, $parameters);
    }

    protected function isStaticMethod(string $method): bool
    {
        $cacheKey = $this->model_class.'::'.$method;

        if (array_key_exists($cacheKey, static::$staticMethodsCache)) {
            return static::$staticMethodsCache[$cacheKey];
        }

        if (! method_exists($this->model_class, $method)) {
            return static::$staticMethodsCache[$cacheKey] = false;
        }

        $reflection = new ReflectionMethod($this->model_class, $method);
        $isStatic = $reflection->isStatic() && $reflection->isPublic();

        return static::$staticMethodsCache[$cacheKey] = $isStatic;
    }

    public static function clearStaticMethodsCache(): void
    {
        static::$staticMethodsCache = [];
    }
}
