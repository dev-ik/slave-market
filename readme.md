#### Установка окружения
Для запуска проекта необходимо установить [Docker](https://www.docker.com/) и [docker-compose](https://docs.docker.com/compose/)


#### Запуск проекта
 * Заходим в папку с проектом 
  ```
     cd /path_to_project
  ```
  * Создать файл с переменными
    ```
       mv ./.env.example ./.env
    ```
    
  * собираем образ
  ```
     docker-compose build 
  ```
  * запускаем докер
  ```
     docker-compose up -d 
  ```
  
   * устанавливаем заисимости
   ```
      docker-compose exec php composer install
   ```
    
   * запускаем тесты
   ```
      docker-compose exec php ./vendor/bin/phpunit tests 
   ```
