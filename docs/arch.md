# Архитектура фрэймворка

Фрэймворк предназначен для построения неинтерактивных приложений, обработчиков запросов.

## Обработака запроса

```plantuml
@startuml

title Обработка запроса

actor User

User -> index.php
index.php -> App : run(rq)
App -> Router : route(rq.url)
App -> Registry : get(handlerId)

database "./registry/*" as fs

Registry -> fs : require
fs -> Registry : valueBuilder
Registry -> ValueBuilder : build()
Registry -> App : handler
App -> handler : __invoke(rq, rs)
handler -> App : rs
App -> Response : send()
App -> index.php
index.php -> User

@enduml
```

## Внедрение зависимостей

Фреймворк содержит решение основанное на реестре Registry и построителе значений ValueBuilder. Реестр ищет скрипты-фабрики значений в файловой системе (return из подключённого файла), если в результате получает ValueBuilder, то вызывает его метод build, который получает зависимости, вызывая Registry::get (косвенная рекурсия), и строит значение.

Любой сервис или контроллер описывается как ValueBuilder и может иметь зависимости или быть чей-то зависимостью.

