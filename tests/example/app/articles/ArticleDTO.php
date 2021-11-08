<?php declare(strict_types=1);

namespace example\articles;

class ArticleDTO {
	public function __construct(
		public string $title,
		public string $annotation,
	) {}
}