<?php declare(strict_types=1);

/**
 * Beyond Lies the Wub
 * @param callable $func
 * @return Closure
 */
function wub(callable $func): Closure {
	return function(...$args) use($func) {
		return function_exists('captainFranco')?
			captainFranco($func, $args) : new \wub\ValueBuilder($func, $args);
	};
}

function make(\Closure $factory): \wub\Make {
	return new \wub\Make($factory);
}