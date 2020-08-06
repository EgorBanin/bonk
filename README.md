#Wub

Минималистичный фрэймворк для построения неинтерактивных приложений, обработчиков запросов. Для обучения веб-разработке, для прототипов. И для провокаций в команде высококлассных специалистов :-).

Основная идея заключается в том, что код фрэймворка в вашем приложении принадлежит вам. Никаких тайных знаний. Объём кода такой, что вы можете разобраться в нём за один подход. А когда придёт время оправдать вашу высокую зарплату объёмом кода в репозитории, перейдя на симфони, выбросить эту поделку будет легко как никогда!

## Точка входа

```php
<?php // index.php

require __DIR__ . '/vendor/autoload.php';

$router = new \wub\Router([
    'GET /' => 'handlers/index.php',
]);
$registry = new \wub\Registry(
    [
        'db' => [
            'fileName' => __DIR__ . '/db.sqlite',
        ]
    ],
    [
        'handlers/' => __DIR__ . '/wub/handlers',
        '' => __DIR__ . '/wub/services',
    ]
);
$app = new \wub\App($router, $registry);
$rq = \wub\HttpRequest::fromGlobals($_SERVER, $_GET, $_POST, $_FILES, $_COOKIE, 'php://input');
$rs = \wub\HttpResponse::ok();
$exitCode = $app->run($rq, $rs, fopen('php://output', 'w'), fopen('php://stderr', 'w'));
exit($exitCode);
```

```
mkdir -d wub/handlers
touch wub/db.php
touch wub/handlers/index.php
```

```php
<?php // wub/db.php

return wub(function(string $fileName) {
    static $db;
    
    if ($db === null) {
        $db = new PDO('sqlite:' . $fileName);
    }
    
    return $db;
})(fn(\wub\Registry $registry) => [$registry->config('db.fileName')]);
```

```php
<?php // wub/handlers/index.php

return wub(fn(PDO $db) => function(\wub\HttpRequest $rq, \wub\HttpResponse $rs) use ($db) {
    $sth = $db->prepare('select * from posts order by views desc limit 10');
    $sth->execute();
    $rows = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $rs->setBody(print_r($rows, true));
})('db.php');
```

## func_all inside

[Функол](https://github.com/EgorBanin/func_all) уже внутри! ]:-> Для ваших прототипов вам наверняка пригодятся такие перлы как _ob_include_ и _obj_init_.

## Роутинг

Роутер очень простой, он получает локатор запроса и возвращает соответсвующий идентификатор обработчика. Просто сопоставляет строки.

```php
<?php // routes.php

return [
    'GET /' => 'handlers/index.php',
    '~^(?<method>GET) /(?<collection>[^/]+)/?$~' => 'handlers/{collection}/index.php',
    '~^(?<method>\S+) /(?<collection>[^/]+)/(?<id>[^/]+)/?$~' => 'handlers/{collection}/{method}.php',
];
```

Три строчки -- вот и весь REST API.

## Шаблонизатор

Фрэймворк предоставляет вам свободу выбора шаблонизатора. Впрочем, не рекомендую заморачиваться с рендерингом. Бахните phtml!

```php
echo \func_all\ob_include(__DIR__ . '/index.phtml', ['posts' => $postRepo->selectTop()]);
```

Расширение phtml помогает не заинклюдить шаблон как обработчик, например. Можете навернуть на это какую-нибудь обвязку с отдельной директорией для шаблонов, с лэйаутами и прочим. Но кому это надо во времена API'шек?

## ORM

Фрэймворк не содержит никаких средств для работы с базами данных и никаких средств для отображения строк таблиц в объекты. Но он содержит кое-что получше! Офигенный совет!

Совет: организуйте репозитории, которые будут предоставлять методы получения и сохранения объектов; Используйте SQL для работы с бд, управляйте транзакциями; Сделайте маппинг явным.

```php
<?php

interface IRepo {
    
    public function get($id): object;

    public function select(int $limit): array;

    public function save(object $obj);

    public function rm(object $obj);

    // и другие базовые методы
    
}

abstract class BaseRepo implements IRepo {
    
    protected Mysql $db;

    protected $table;
    
    public function __construct(Mysql $db, string $tableName, string $primaryKeyName = 'id') {
        $this->db = $db;
        $this->table = $this->db->table($tableName, $primaryKeyName);
    }

    public function get($id): object {
        return $this->map($this->table->get($id));
    }

    public function select(int $limit): array {
        $rows = $this->table->select([], '*', null, $limit);
        
        return array_map([$this, 'map'], $rows);
    }

    public function save(object $obj) {
        $row = $this->pam($obj);
        $id = $row[$this->table->pk()]?? null;
        if ($id !== null) {
            $this->table->set($id, $row);
        } else {
            $id = $this->table->insert($row);
            \func_all\obj_init($obj, [$this->table->pk() => $id]);
        }
    }

    public function rm(object $obj) {
        $row = $this->pam($obj);
        $id = $row[$this->table->pk()]?? null;
        if ($id !== null) {
            $this->table->rm($id);
        } 
    }

    abstract protected function map(array $row): object;

    abstract protected function pam(object $obj): array;

}

class PostRepo extends BaseRepo {

    public function __construct(Mysql $db) {
        parent::__construct($db, 'posts');
    }

    public function selectTop(int $limit): array {
        // запрос исключительно для демонстрации, в реальных проектах для таких штук используйте сводные таблицы и прочую оптимизацию
        $result = $this->db->query('
            select `posts`.* from `posts`
            inner join (
                select
                    `postId`,
                    sum(
                        1 / greatest(1, round((unix_timestamp() - `ct`) / 3600))
                    ) as `rate`
                from `comments`
                group by `postId`
            ) as `rates`
            on `rates`.`postId` = `posts`.`id`
            order by `rates`.`rate` desc
            limit :limit
        ', [':limit' => max(1, min(100, $limit))]);
    
        return array_map([$this, 'map'], $result->rows());
    }

    protected function map(array $row): object {
        $post = new Post();
        
        return \func_all\obj_init($post, $row);
    }

    protected function pam(object $obj): array {
        return \func_all\obj_to_array($obj);
    }

}
```

Тут я использовал свою [библиотечку](https://github.com/EgorBanin/phpmysql) для работы с MySQL. Но вы наверняка можете бахнуть такое и на PDO или подключить доктрину или элоквент.

## Аутентификация и авторизация

Напишите сами. Никаких правил! Стандартный механизм сессий или сторонний сервис аутентификации, куки или JWT, RBAC с ролями в вашей бд или oAuth микросервиса -- релизуйте то что вам надо, не заморачиваясь с натягиванием вашего случая на базовую абстракцию.

Для тех кому нужна-таки помощь, я накатал библиотечку (TODO) в отдельном репе.


## Безопасность

Вместе со свободой, которую дарит вам фрэймворк, вы получаете и ответственность. Фрэймворк умеет только пердотвратить
 инклюд файла в Registry за пределами указанной в конфиге директории. Если вы позволяете пользователю указать подключаемый файл (как в случае с обработчиками при динамическом роутинге), то вы должны позаботится, чтобы в соответствующей директории были только те файлы, которые пользователю подключать можно.
 
Для защиты от XSS пользуйтесь `htmlspecialchars` или средства вашего шаблонизатора, для защиты от CSRF реализуте токены, для защиты от SQL-инъекций используйте соответсвующие возможности своей библиотеки для работы с бд.