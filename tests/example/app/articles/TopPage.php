<?php declare(strict_types=1);

namespace example\articles;

class TopPage {

	public function render(array $articles): string {
		return $this->includeTpl(__DIR__ . '/topPage.phtml', ['articles' => $articles]);
	}

	/**
	 * @param string $file
	 * @param array $params
	 */
	private function includeTpl(): string {
		extract(func_get_arg(1));
		ob_start();
		require func_get_arg(0);
		return ob_get_clean();
	}
}