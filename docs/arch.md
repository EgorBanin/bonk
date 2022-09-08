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
Router --> App: handlerId
App -> Registry : get(handlerId)

database "./registry/*" as fs

Registry -> fs : require
fs --> Registry : factory
Registry -> Factory : __invoke(registry)
Factory --> Registry: handler
Registry --> App : handler
App -> handler : __invoke(rq, rs)
handler --> App : rs
App -> Response : send()
App --> index.php: exitCode
index.php --> User

@enduml
```

## Внедрение зависимостей

Фреймворк содержит решение основанное на реестре Registry и фабрике зависимостей Factory. Реестр получает зависимости, подключая файл скрипта-фабрики (скрипт возвращает фабрику через return).

Любой сервис или обработчик запроса является экземпляром Factory и может иметь зависимости или быть чьей-то зависимостью. Например, объект приложения App находит нужный обработчик запроса через реестр.

