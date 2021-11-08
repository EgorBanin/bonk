<?php declare(strict_types=1);

namespace frm;

return make(fn(
	\example\articles\Service $articles,
) => function(HttpRequest $rq, HttpResponse $rs) use($articles) {
	$page = new \example\articles\TopPage();

	return $rs->setBody($page->render($articles->top()));
})->with(
	articles: 'articles/service.php',
);