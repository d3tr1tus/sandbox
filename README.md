# Atreo.digital sandbox

## API Installation

```
composer install
composer db:recreate
```

## Web Installation

```
npm install
```

## Configuration

```
cp .env.dist .env
```

## Run the project

```
composer dev # api
npm run dev # web build
```

V případě že používáte https, tak je potřeba otevřít konzoli, zobrazit nenačtený js soubor (https://127.0.0.1:3000/backend/dist/index.js) a schválit mu vyjímku pro ssl certifikát.


## Updating Swagger documentation


```
composer swagger
```

Examples: https://github.com/zircote/swagger-php/tree/master/Examples
