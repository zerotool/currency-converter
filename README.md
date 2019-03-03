# Symfony 4.2 Currency Converter
- Docker (php + nginx + postgres + adminer + redis)
- Codeception 
- XDebug

## Requirements
1. Docker, docker-compose.
2. Http ports 8084, 8085 available on the host machine.

## Install

1. Create `.env` file from `.env.dist` file and set correct vars values in it
2. Run:
```
$ ./prepare_environment.sh
```

## Usage
### Configuration

Located in `config/services.yaml`:

`app.currency.rates_provider: 'cbr' # ecb|cbr`
`app.currency.base_currency: 'RUB' # EUR|RUB`

Run console command to refresh rates after any changes of those two parameters.

### Console application to refresh the rates

Run command:
```
docker-compose exec -T php bin/console app:currency:load-rates
```

### REST application to convert currency

Send POST `http://localhost:8085/convert` with JSON:
```
{
	"from_currency_code": "RUB",
	"to_currency_code": "EUR",
	"amount": "77.1239"
}
```

Success Response HTTP 200:
```
{
    "success": true,
    "data": {
        "amount_converted": 1.0318
    }
}
```

Error Response HTTP 400:
```
{
    "success": false,
    "data": {
    },
    "error": "Unknown currency code: XYZ"
}
```

## Available URLs:

* http://localhost:8085/ - REST Api base URL

* http://localhost:8084/ - adminer

Adminer credentials:<br>
* System: PostgreSQL<br>
* Server: postgres<br>
* Username: symfony<br>
* Password: 123456

## Codeception

run all tests under folder
```
$ cd codeception
$ php ../vendor/bin/codecept run tests/Functional
```

run one test in debug mode
```
$ cd codeception
$ php ../vendor/bin/codecept run tests/Functional/RestTestCest.php --debug
```

build tester classes
```
$ cd codeception
$ php ../vendor/bin/codecept build
```

## Unit tests

1. Copy `phpunit.xml.dist` to `phpunit.xml`, change parameters as you like.
2. Run `phpunit`.

## TODO

1. Add logging support
2. Implement Redis caching for conversion
3. Add IaC deployment script
4. Add DB index on `currency_rate`.`currency_code` field
5. Implement validation for incoming XML data (currency code 3 characters max, rate should be a number)
6. Wrap currency converter into a bundle to be able to embed it
7. Implement more strict types in Utils functions

## Contact Information

[st.erokhin@gmail.com](mailto:st.erokhin@gmail.com)

Copyright 2019 Stanislav Erokhin
