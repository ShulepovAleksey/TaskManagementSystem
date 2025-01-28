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

Для запуска тестов нужно внутри контейнера (выполнить п.2 из раздела <strong>Установка</strong>) использовать команды
   > vendor/bin/codecept run tests/unit/models/TaskTest.php
   > vendor/bin/codecept run tests/unit/models/TaskLogicTest.php
