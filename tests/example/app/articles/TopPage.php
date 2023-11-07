<?php declare(strict_types=1);

namespace example\articles;

class TopPage {

	public function render(array $articles): string {
		return \frm\ob_include(__DIR__ . '/topPage.phtml', ['articles' => $articles]);
	}
}