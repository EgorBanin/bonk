<?php declare(strict_types=1);

namespace frm;

return make(fn(
	\PDO $pdo,
) => function(CliRequest $rq, CliResponse $rs) use($pdo) {
	$pdo->query('
		create table if not exists articles (
		    title text,
		    annotation text,
		    content text,
		    views integer,
		    ct integer,
		    ut integer
		)
	');
	$pdo->query('
		create index utViews on articles (ut, views)
	');
	$pdo->query('
		insert into articles (
			title,
			annotation,
			content,
			views,
			ct,
			ut
		) values (
			"Почему амбивалентно медиапланирование?",
			"Эскапизм начинает повседневный имидж предприятия. Итак, ясно, что чувство гомогенно искажает стресс. Интеракционизм вполне вероятен. Ассоцианизм просветляет институциональный мониторинг активности, как и предсказывают практические аспекты использования принципов гештальпсихологии в области восприятия, обучения, развития психики, социальных взаимоотношений. План размещения наиболее полно выбирает имидж.",
			"Эскапизм начинает повседневный имидж предприятия. Итак, ясно, что чувство гомогенно искажает стресс. Интеракционизм вполне вероятен. Ассоцианизм просветляет институциональный мониторинг активности, как и предсказывают практические аспекты использования принципов гештальпсихологии в области восприятия, обучения, развития психики, социальных взаимоотношений. План размещения наиболее полно выбирает имидж.

Акцентуация отталкивает экзистенциальный стимул, здесь описывается централизующий процесс или создание нового центра личности. Изменение глобальной стратегии, конечно, вполне выполнимо. Рекламный клаттер, конечно, начинает фирменный аутизм. CTR отражает архетип. Правда, специалисты отмечают, что конкурент индуцирует субъект, что отмечают такие крупнейшие ученые как Фрейд, Адлер, Юнг, Эриксон, Фромм.

НЛП позволяет вам точно определить какие изменения в субьективном опыте надо произвести, чтобы душа консолидирует эмпирический контент. Процесс стратегического планирования программирует закон. Нишевый проект, например, понимает филосовский диктат потребителя. Интеракционизм основан на анализе телесмотрения.",
			0,
			' . time() . ',
			' . time() . '
		)
	');

	return $rs->setOutput('');
})->with(
	pdo: 'pdo.php',
);