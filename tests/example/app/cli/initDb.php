<?php declare(strict_types=1);

namespace frm;

return factory(fn(
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
		);
		delete from articles;
	');
	$pdo->query('
		drop index if exists utViews;
		create index utViews on articles (ut, views);
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
		), (
			"Изоморфный тетрахорд: гипотеза и теории",
			"Дифференциация всекомпонентна. Пуанта положительна. Гармоническое микророндо порождает рок-н-ролл 50-х. Отсюда естественно следует, что интеграл Пуассона начинает график функции многих переменных. В заключении добавлю, дифференциальное исчисление заканчивает многомерный канал, благодаря широким мелодическим скачкам. Действительно, дисперсия небезынтересно заканчивает длительностный рок-н-ролл 50-х.",
			"Дифференциация всекомпонентна. Пуанта положительна. Гармоническое микророндо порождает рок-н-ролл 50-х. Отсюда естественно следует, что интеграл Пуассона начинает график функции многих переменных. В заключении добавлю, дифференциальное исчисление заканчивает многомерный канал, благодаря широким мелодическим скачкам. Действительно, дисперсия небезынтересно заканчивает длительностный рок-н-ролл 50-х.

			Замкнутое множество иллюстрирует midi-контроллер. Поле направлений существенно уравновешивает возрастающий вектор, в таких условиях можно спокойно выпускать пластинки раз в три года. Канал, в первом приближении, полифигурно вызывает детерминант. Кластерное вибрато, так или иначе, притягивает абстрактный аккорд. Прямоугольная матрица, следовательно, восстанавливает ритмоформульный звукоряд. Нормальное распределение синхронно оправдывает изоморфный звукоряд.
			
			Шоу-бизнес, и это особенно заметно у Чарли Паркера или Джона Колтрейна, использует определенный фьюжн, дальнейшие выкладки оставим студентам в качестве несложной домашней работы. Дело в том, что лайн-ап начинает график функции. Интеграл по бесконечной области заканчивает хорус.",
			0,
			' . time() . ',
			' . time() . '
		);
	');

	return $rs->setOutput('Done');
})->with(
	pdo: 'pdo.php',
);