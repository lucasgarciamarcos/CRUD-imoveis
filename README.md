# CRUD-imoveis
API de Imóveis utilizando PHP 8.2, Lumem 10, SQLite e PHPUnit.


Comandos para rodar o projeto: 


git clone https://github.com/lucasgarciamarcos/CRUD-imoveis.git 


composer self-update                // Certifica versionamento atual do Composer 


composer install                    // Instala dependências Lumem 


php artisan migrate                 // Faz o migrate do DB 


php -S localhost:8000 -t public     // Up do projeto em localhost 


./vendor/bin/phpunit                // Roda os testes unitários 


http://localhost:8000/home          // Acessa view CRUD