# import weather

### Installation

1. 😀 Склонировать данный репозиторий
2. Перейти в папку .docker
3. Сделать ```docker compose build``` в консоли внутри папки .docker
4. Сделать ```docker compose up``` в консоли внутри папки .docker
5. Сделать ```composer install``` внутри контейнера php-fpm
6. Выполняем все миграции ```php bin/console doctrine:migrations:migrate```
7. для того чтобы попасть в админку по http://localhost/admin нужно:
    a. Сделать ```php bin/console doctrine:fixtures:load``` внутри контейнера php-fpm
    b. Перейти в браузере в http://localhost/admin
8. Импортировать данные можно 2 способами:
    a. Перейти в браузере в http://localhost/weather/import
    b. Можно так ```php bin/console app:import-weather```
9. Для того, чтобы начать тесты, нужно внутри контейнера php-fpm запустить в консоли ```php bin/phpunit```
10. Можно зайти в браузере и запустить http://localhost/api/weather.json
 
![Иллюстрация к проекту](https://img001.prntscr.com/file/img001/yeogF1HNTMKSatcM1QSBIw.png)