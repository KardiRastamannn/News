<?php

namespace News\Core;

use ReflectionClass;
use ReflectionException;

class Container
{
    protected array $bindings = [];

    public function bind(string $abstract, callable $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function resolve(string $class)
    {
        // Ha van manuálisan regisztrált binding
        if (isset($this->bindings[$class])) {
            return call_user_func($this->bindings[$class], $this);
        }

        try {
            $reflector = new ReflectionClass($class);

            // Ha nem példányosítható (pl. interface, abstract), hibázik
            if (!$reflector->isInstantiable()) {
                throw new \Exception("Class {$class} is not instantiable.");
            }

            $constructor = $reflector->getConstructor();

            // Ha nincs konstruktor, csak példányosítjuk
            if (is_null($constructor)) {
                return new $class;
            }

            // Konstruktor paraméterei
            $parameters = $constructor->getParameters();
            $dependencies = [];

            foreach ($parameters as $parameter) {
                $type = $parameter->getType();

                if ($type === null) {
                    throw new \Exception("Cannot resolve parameter \${$parameter->getName()} of class {$class}");
                }

                $dependencies[] = $this->resolve($type->getName());
            }

            return $reflector->newInstanceArgs($dependencies);
        } catch (ReflectionException $e) {
            throw new \Exception("Unable to resolve class: {$class}. Error: " . $e->getMessage());
        }
    }
    // DI Injections
	public function addInjections(){
		$this->bind(News\Core\Connection::class, function() {
			return new News\Core\Connection();
		});
		
		$this->bind(News\Controllers\GuestController::class, function($container) {
			return new News\Controllers\GuestController(
				$container->resolve(News\Core\Connection::class),
				$container->resolve(NewsController::class),
				$container->resolve(AdminController::class)
		
			);
		});
		
		$this->bind(News\Controllers\NewsController::class, function($container) {
			return new News\Controllers\NewsController(
				$container->resolve(News\Core\Connection::class)
			);
		});
		
		$this->bind(News\Controllers\AdminController::class, function($container) {
			return new News\Controllers\AdminController(
				$container->resolve(News\Core\Connection::class)
			);
		});
	}
}