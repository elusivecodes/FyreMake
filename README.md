# FyreMake

**FyreMake** is a free, open-source collection of commands for generating files for [*FyrePHP*](https://github.com/elusivecodes/FyrePHP).


## Table Of Contents
- [Installation](#installation)
- [Commands](#commands)
    - [Make Behavior](#make-behavior)
    - [Make Cell](#make-cell)
    - [Make Cell Template](#make-cell-template)
    - [Make Command](#make-command)
    - [Make Config](#make-config)
    - [Make Controller](#make-controller)
    - [Make Element](#make-element)
    - [Make Entity](#make-entity)
    - [Make Helper](#make-helper)
    - [Make Job](#make-job)
    - [Make Lang](#make-lang)
    - [Make Layout](#make-layout)
    - [Make Middleware](#make-middleware)
    - [Make Migration](#make-migration)
    - [Make Model](#make-model)
    - [Make Template](#make-template)



## Installation

**Using Composer**

```
composer require fyre/make
```

In PHP:

```php
use Fyre\Command\CommandRunner;

CommandRunner::addNamespace('\Fyre\Make');
```


## Commands

### Make Behavior

Generate a new [*Behavior*](https://github.com/elusivecodes/FyreORM#behaviors).

```php
CommandRunner::run('make:behavior', ['Example']);
```

From the CLI (using [*FyrePHP*](https://github.com/elusivecodes/FyrePHP)).

```bash
./bin/fyre make:behavior Example
```

### Make Cell

Generate a new [*Cell*](https://github.com/elusivecodes/FyreView#cells).

```php
CommandRunner::run('make:cell', ['Example']);
```

From the CLI (using [*FyrePHP*](https://github.com/elusivecodes/FyrePHP)).

```bash
./bin/fyre make:cell Example
```

### Make Cell Template

Generate a new [*cell template*](https://github.com/elusivecodes/FyreView#cells).

```php
CommandRunner::run('make:cell_template', ['Example.display']);
```

From the CLI (using [*FyrePHP*](https://github.com/elusivecodes/FyrePHP)).

```bash
./bin/fyre make:cell_template Example.display
```

### Make Command

Generate a new [*Command*](https://github.com/elusivecodes/FyreCommand#commands).

```php
CommandRunner::run('make:command', ['Example']);
```

From the CLI (using [*FyrePHP*](https://github.com/elusivecodes/FyrePHP)).

```bash
./bin/fyre make:command Example
```

### Make Config

Generate a new [*Config*](https://github.com/elusivecodes/FyreConfig) file.

```php
CommandRunner::run('make:config', ['example']);
```

From the CLI (using [*FyrePHP*](https://github.com/elusivecodes/FyrePHP)).

```bash
./bin/fyre make:config example
```

### Make Controller

Generate a new [*controller*](https://github.com/elusivecodes/FyreRouter#controller-routes).

```php
CommandRunner::run('make:controller', ['Example']);
```

From the CLI (using [*FyrePHP*](https://github.com/elusivecodes/FyrePHP)).

```bash
./bin/fyre make:controller Example
```

### Make Element

Generate a new [element](https://github.com/elusivecodes/FyreView#elements).

```php
CommandRunner::run('make:element', ['example']);
```

From the CLI (using [*FyrePHP*](https://github.com/elusivecodes/FyrePHP)).

```bash
./bin/fyre make:element example
```

### Make Entity

Generate a new [*Entity*](https://github.com/elusivecodes/FyreEntity).

```php
CommandRunner::run('make:entity', ['Example']);
```

From the CLI (using [*FyrePHP*](https://github.com/elusivecodes/FyrePHP)).

```bash
./bin/fyre make:entity Example
```

### Make Helper

Generate a new [*Helper*](https://github.com/elusivecodes/FyreView#helpers).

```php
CommandRunner::run('make:helper', ['Example']);
```

From the CLI (using [*FyrePHP*](https://github.com/elusivecodes/FyrePHP)).

```bash
./bin/fyre make:helper Example
```

### Make Job

Generate a new queue [*Job*](https://github.com/elusivecodes/FyreQueue).

```php
CommandRunner::run('make:job', ['Example']);
```

From the CLI (using [*FyrePHP*](https://github.com/elusivecodes/FyrePHP)).

```bash
./bin/fyre make:job Example
```

### Make Lang

Generate a new [*language*](https://github.com/elusivecodes/FyreLang) file.

```php
CommandRunner::run('make:lang', ['Example']);
```

From the CLI (using [*FyrePHP*](https://github.com/elusivecodes/FyrePHP)).

```bash
./bin/fyre make:lang Example
```

### Make Layout

Generate a new view [*layout*](https://github.com/elusivecodes/FyreView#layouts) template.

```php
CommandRunner::run('make:layout', ['default']);
```

From the CLI (using [*FyrePHP*](https://github.com/elusivecodes/FyrePHP)).

```bash
./bin/fyre make:layout default
```

### Make Middleware

Generate a new [*Middleware*](https://github.com/elusivecodes/FyreMiddleware#middleware).

```php
CommandRunner::run('make:middleware', ['Example']);
```

From the CLI (using [*FyrePHP*](https://github.com/elusivecodes/FyrePHP)).

```bash
./bin/fyre make:middleware Example
```

### Make Migration

Generate a new [*Migration*](https://github.com/elusivecodes/FyreMigration#migrations).

```php
CommandRunner::run('make:migration');
```

From the CLI (using [*FyrePHP*](https://github.com/elusivecodes/FyrePHP)).

```bash
./bin/fyre make:migration
```

### Make Model

Generate a new [*Model*](https://github.com/elusivecodes/FyreORM#models).

```php
CommandRunner::run('make:model', ['Example']);
```

From the CLI (using [*FyrePHP*](https://github.com/elusivecodes/FyrePHP)).

```bash
./bin/fyre make:model Example
```

### Make Template

Generate a new view [*template*](https://github.com/elusivecodes/FyreView).

```php
CommandRunner::run('make:template', ['Example.index']);
```

From the CLI (using [*FyrePHP*](https://github.com/elusivecodes/FyrePHP)).

```bash
./bin/fyre make:template Example.index
```