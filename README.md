# h-challenge

### Are you thirsty?

![alt text](screenshot.png)


```
Note

I didn't add all the tests I could because this is a coding challenge (not a real project) and because of time it would take. I tested a couple use cases and the most important domain logic.
```

## Setup

Setup the infrastructure

```
$ make setup
```

Performs the following tasks:

- Builds the containers
- Installs composer dependencies

## Start

Start the application (vending machine)

```
$ make start
```

## Up/Down

Brings the application up

```
$ make up
```

Brings the application down

```
$ make down
```

## Testing

To run tests execute the following:

```
$ make test
```

## Machine

To execute a command inside the machine run the following:

```
$ make console <command-to-execute>
```

## Other

- Symfony 5.2
- PHP 7.4-cli
