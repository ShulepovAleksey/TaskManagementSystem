# Описание
Приложение позволяется просматривать, создавать, редактировать и удалять задачи. Также на форме просмотра задачи доступны кнопки изменения статуса: Новая -> В процессе, В процессе -> Завершена.

# Требования

1. Docker свежей версии

# Установка

1. Выполнить команду в корневой папке проекта
   > docker compose -p task-management-system -f docker\docker-compose.yml up -d
2. Зайти в контейнер
   > docker compose -p task-management-system -f docker\docker-compose.yml exec web bash
3. Установить зависимости
   > composer install
4. Создать структуру БД
   > php yii migrate
5. Проект будет доступен по адресу http://localhost/task

# Архитектура и используемые паттерны
Приложение создано на основе шаблона yii2-basic, причем я оставил нетронутыми стандартный контроллер, модели, представления и тесты. Приложение собрано как контейнер докер для простоты развертывания и конфигурирования. Использован PHP 8.3.16 (на 8.4.3 шаблон yii2-basic быстро запустить не смог), Postgres, Yii2.
<br>
Модели задач сгруппированы в отдельной папке models\Task\. Модель Task унаследована от Model и не привязана к СУБД, но в ней описаны бизнес-логика и правила валидации. В классе TaskStatus через тип enumeration заданы допустимые варианты статусов задач. Для изменения логики задачи в зависимости от ее статуса реализован паттерн <strong>Состояние</strong>, где Task выступает контекстом, а классы из папки TaskState выступают конкретными состояниями.
<br>
Представления сгенерированы с помощью gii и находятся в папке views/task/
<br>
Контроллер TaskController максимально упрощен, т.е. по сути является связующим звеном между представлениями и бизнес-логикой.
<br>
Бизнес-логика описана (помимо моделей) в TaskLogic. Она использует данные модели, которые получает от провайдера, описанного интерфейсом AbstractProvider, реализуя таким образом паттерн <strong>Стратегия</strong>. Конкретная реализация DatabaseProvider использует отдельную модель TaskDB, в которой описан минимум информации, т.к. валидация сделана на уровне бизнес-логики, а не на уровне взаимодействия с хранилищем. Все эти классы располагаются в папке components\

# Тестирование

Для запуска тестов нужно внутри контейнера (выполнить п.2 из раздела <strong>Установка</strong>) использовать команду 
   > vendor/bin/codecept run tests/unit/models/TaskTest.php

TESTING
-------

Tests are located in `tests` directory. They are developed with [Codeception PHP Testing Framework](https://codeception.com/).
By default, there are 3 test suites:

- `unit`
- `functional`
- `acceptance`

Tests can be executed by running

```
vendor/bin/codecept run
```

The command above will execute unit and functional tests. Unit tests are testing the system components, while functional
tests are for testing user interaction. Acceptance tests are disabled by default as they require additional setup since
they perform testing in real browser. 


### Running  acceptance tests

To execute acceptance tests do the following:  

1. Rename `tests/acceptance.suite.yml.example` to `tests/acceptance.suite.yml` to enable suite configuration

2. Replace `codeception/base` package in `composer.json` with `codeception/codeception` to install full-featured
   version of Codeception

3. Update dependencies with Composer 

    ```
    composer update  
    ```

4. Download [Selenium Server](https://www.seleniumhq.org/download/) and launch it:

    ```
    java -jar ~/selenium-server-standalone-x.xx.x.jar
    ```

    In case of using Selenium Server 3.0 with Firefox browser since v48 or Google Chrome since v53 you must download [GeckoDriver](https://github.com/mozilla/geckodriver/releases) or [ChromeDriver](https://sites.google.com/a/chromium.org/chromedriver/downloads) and launch Selenium with it:

    ```
    # for Firefox
    java -jar -Dwebdriver.gecko.driver=~/geckodriver ~/selenium-server-standalone-3.xx.x.jar
    
    # for Google Chrome
    java -jar -Dwebdriver.chrome.driver=~/chromedriver ~/selenium-server-standalone-3.xx.x.jar
    ``` 
    
    As an alternative way you can use already configured Docker container with older versions of Selenium and Firefox:
    
    ```
    docker run --net=host selenium/standalone-firefox:2.53.0
    ```

5. (Optional) Create `yii2basic_test` database and update it by applying migrations if you have them.

   ```
   tests/bin/yii migrate
   ```

   The database configuration can be found at `config/test_db.php`.


6. Start web server:

    ```
    tests/bin/yii serve
    ```

7. Now you can run all available tests

   ```
   # run all available tests
   vendor/bin/codecept run

   # run acceptance tests
   vendor/bin/codecept run acceptance

   # run only unit and functional tests
   vendor/bin/codecept run unit,functional
   ```

### Code coverage support

By default, code coverage is disabled in `codeception.yml` configuration file, you should uncomment needed rows to be able
to collect code coverage. You can run your tests and collect coverage with the following command:

```
#collect coverage for all tests
vendor/bin/codecept run --coverage --coverage-html --coverage-xml

#collect coverage only for unit tests
vendor/bin/codecept run unit --coverage --coverage-html --coverage-xml

#collect coverage for unit and functional tests
vendor/bin/codecept run functional,unit --coverage --coverage-html --coverage-xml
```

You can see code coverage output under the `tests/_output` directory.
