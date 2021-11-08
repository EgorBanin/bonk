<?php declare(strict_types=1);

namespace example\articles;

class Repo {
	public function __construct(
		private \PDO $pdo,
	) {}

	public function select(): array {
		$sth = $this->pdo->prepare('
			select *
			from articles
			order by views desc
		');
		$sth->execute();
		$articles = [];
		while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
			$articles[] = $this->map($row);
		}

		return $articles;
	}

	public function map(array $row) {
		return new ArticleDTO(
			$row['title'],
			$row['annotation'],
		);
	}

}