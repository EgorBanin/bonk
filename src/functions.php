<?php declare(strict_types=1);

namespace frm;

function make(\Closure $factory): \frm\Make {
	return new \frm\Make($factory);
}
