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
$runner = $container->use(CommandRunner::class);

$runner->addNamespace('\Fyre\Make\Commands');
```


## Commands

### Make Behavior

Generate a new [*Behavior*](https://github.com/elusivecodes/FyreORM#behaviors).

```php
$runner->run('make:behavior', ['Example']);
```

### Make Cell

Generate a new [*Cell*](https://github.com/elusivecodes/FyreView#cells).

```php
$runner->run('make:cell', ['Example']);
```

### Make Cell Template

Generate a new [*cell template*](https://github.com/elusivecodes/FyreView#cells).

```php
$runner->run('make:cell_template', ['Example.display']);
```

### Make Command

Generate a new [*Command*](https://github.com/elusivecodes/FyreCommand#commands).

```php
$runner->run('make:command', ['Example']);
```

### Make Config

Generate a new [*Config*](https://github.com/elusivecodes/FyreConfig) file.

```php
$runner->run('make:config', ['example']);
```

### Make Controller

Generate a new [*controller*](https://github.com/elusivecodes/FyreRouter#controller-routes).

```php
$runner->run('make:controller', ['Example']);
```

### Make Element

Generate a new [element](https://github.com/elusivecodes/FyreView#elements).

```php
$runner->run('make:element', ['example']);
```

### Make Entity

Generate a new [*Entity*](https://github.com/elusivecodes/FyreEntity).

```php
$runner->run('make:entity', ['Example']);
```

### Make Helper

Generate a new [*Helper*](https://github.com/elusivecodes/FyreView#helpers).

```php
$runner->run('make:helper', ['Example']);
```

### Make Job

Generate a new queue [*Job*](https://github.com/elusivecodes/FyreQueue).

```php
$runner->run('make:job', ['Example']);
```

### Make Lang

Generate a new [*language*](https://github.com/elusivecodes/FyreLang) file.

```php
$runner->run('make:lang', ['Example']);
```

### Make Layout

Generate a new view [*layout*](https://github.com/elusivecodes/FyreView#layouts) template.

```php
$runner->run('make:layout', ['default']);
```

### Make Middleware

Generate a new [*Middleware*](https://github.com/elusivecodes/FyreMiddleware#middleware).

```php
$runner->run('make:middleware', ['Example']);
```

### Make Migration

Generate a new [*Migration*](https://github.com/elusivecodes/FyreMigration#migrations).

```php
$runner->run('make:migration');
```

### Make Model

Generate a new [*Model*](https://github.com/elusivecodes/FyreORM#models).

```php
$runner->run('make:model', ['Example']);
```

### Make Policy

Generate a new [*Policy*](https://github.com/elusivecodes/FyreAuth#policies).

```php
$runner->run('make:policy', ['Example']);
```

### Make Template

Generate a new view [*template*](https://github.com/elusivecodes/FyreView).

```php
$runner->run('make:template', ['Example.index']);
```
