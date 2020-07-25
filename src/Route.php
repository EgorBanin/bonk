<?php declare(strict_types=1);

namespace wub;

class Route implements IRoute {

    private $handlerId;

    private $params;

    public function __construct(string $handlerId, array $params) {
        $this->handlerId = $handlerId;
        $this->params = $params;
    }

    public function getHandlerId(): string {
        return $this->handlerId;
    }

    public function getParams(): array {
        return $this->params;
    }

}