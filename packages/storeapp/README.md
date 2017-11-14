phprum
======

### What is this?

Experimental project of advanced task managament app.

### Why?

For fun. Because I can.

### Architecture

App uses symfony framework

Domain model is placed outside bundle in src/PHPRum directory. It may be overkill for now
but Domain model may grow in complexity quickly. Domain model is tested with PHPSpec.

Domain is seperated from framework code. However for practical purposes domain entities are extended in framework bundle to
allow framework "magic" to work.

For async user notifications app uses AMQP and WebSocket server. Single script reads from queue and sends
notifications via WebSocket.

Other code follows standard symfony practices.

RESTful api autorization is done with JWT.

Css bootstrap is used on front-end.

### Domain

Domain vision:

Main purpose is to increase productivity. Do more in less time. To achieve this:

- Priorytetize tasks to focus on important ones and don't waste time on insignificant problems
- Store information about tasks and projects to not waste time on re-searching lost information
- Simplyfy adding tasks from various sources so information is stored in one place only

### Starting WebSocket server

```php bin/console user:notification:server```

### Testing

To run Domain model tests:

```vendor/bin/phpspec run```

Symfony compontents (forms etc.) are tested with classic PHPUnit.

Functional tests with codeception:

```vendor/bin/codecept run acceptance``` 

selenium server is required. Acceptance test can be run also using phanton js:

```vendor/bin/codecept run acceptance --env phantom``` 

To start selenium server:

```selenium-server -port 4444```

To start phantom

```phantomjs --webdriver=4444```

### Deployment

Can be deployed using deployer script

```php vendor/bin/dep deploy```



