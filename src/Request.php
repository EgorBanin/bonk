<?php declare(strict_types=1);

namespace frm;

interface IRequest {

	public function getLocator(): string;

	public function addAppParams(array $params);

}