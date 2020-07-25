<?php declare(strict_types=1);

namespace wub;

class CliRequest implements IRequest {

	private array $argv;
	private string $input;
	private array $appParams = [];

	public function __construct(array $argv, string $input) {
		$this->argv = $argv;
		$this->input = $input;
	}

	public function getLocator(): string {
		return \implode(' ', $this->argv);
	}

	public function addAppParams(array $params) {
		$this->appParams = \array_merge($this->appParams, $params);
	}

	public function getAppParam(string $name, $default = null) {
		return \array_key_exists($name, $this->appParams)? $this->appParams[$name] : $default;
	}

	public function getInput(): string {
		return $this->input;
	}

	public function getArgv(): array {
		return $this->argv;
	}

}