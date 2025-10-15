### Pré-requisitos:

-   [PHP 8.3](https://www.php.net/)
-   [Composer](https://getcomposer.org/)
-   [Node.js](https://nodejs.org/en/)
-   [NPM](https://www.npmjs.com/)

### Configuração:

1. Clone o repositório

```bash
git clone https://github.com/rodriguesxxx/HDG.git

```

2. Instale as dependências do PHP

```bash
    composer install && npm install
```

3. Copie o arquivo .env.example para .env

```bash
    cp .env.example .env #no .env informe as info do banco
```

4. Gere uma nova chave para a aplicação

```bash
    php artisan key:generate
```

5. Crie o banco de dados

```bash
    touch database/database.sqlite #Esse comando cria o arquivo do sqlite, pode ser feito pela interface do gerenciador de arquivos também
    php artisan migrate --seed
```

6. Inicie o servidor

```bash
    composer run dev
```
