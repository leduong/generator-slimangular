# generator-slimangular [![Build Status](https://secure.travis-ci.org/leduong/generator-slimangular.png?branch=master)](https://travis-ci.org/leduong/generator-slimangular)

> [Yeoman](http://yeoman.io) generator


## Getting Started

### What is Yeoman?

Trick question. It's not a thing. It's this guy:

![](http://i.imgur.com/JHaAlBJ.png)

Basically, he wears a top hat, lives in your computer, and waits for you to tell him what kind of application you wish to create.

Not every new computer comes with a Yeoman pre-installed. He lives in the [npm](https://npmjs.org) package repository. You only have to ask for him once, then he packs up and moves into your hard drive. *Make sure you clean up, he likes new and shiny things.*


# The Angular Slim generator

A [Yeoman](http://yeoman.io) generator for [AngularJS](http://angularjs.org) and [Slim](http://www.slimframework.com/).

Slim is a PHP-based micro-framework.  For AngularJS integration with other micro-frameworks, see https://github.com/codeguy/Slim (https://github.com/codeguy/Slim).

## Installation

Install [Git](http://git-scm.com), [node.js](http://nodejs.org), and [PHP 5.4](http://www.php.net/).  The development mode also requires either [SQLite](http://www.sqlite.org), [MySQL](http://www.mysql.com/), or [PostgreSQL](http://www.postgresql.org/).

Install Yeoman:

    npm install -g yo

Install the Angular-Slim generator:

    npm install -g https://github.com/leduong/generator-slimangular/archive/v1.1.tar.gz

The above prerequisites can be installed to a VM using the [Angular Slim provisioner](https://github.com/leduong/generator-slimangular).

## Creating a Slim service

In a new directory, generate the service:

    mkdir new-app && cd $_
    yo slimangular:app

Install [Composer](https://getcomposer.org/):

    curl -s http://getcomposer.org/installer | php

Install dependencies manually if composer was not pre-installed:

    php composer.phar update

Run the service:

    php -S 127.0.0.1:8080 -t public

Your service will run at [http://localhost:8080](http://localhost:8080).


## Creating a persistent model

Generate the model (lowercase):

    yo slimangular:model [mymodel]

You will be asked to specify attributes for the model, where each attribute has the following:

- a name (TitleCase "First Name", "Last Name", "Birthday"...)
- a type (String, Integer, Float, Boolean, Date, Enum)
- for a String attribute, an optional minimum and maximum length
- for a numeric attribute, an optional minimum and maximum value
- for a Date attribute, an optional constraint to either past values or future values
- for an Enum attribute, a list of enumerated values
- whether the attribute is required

Files that are regenerated will appear as conflicts.  Allow the generator to overwrite these files as long as no custom changes have been made.

Install dependencies manually if composer was not pre-installed:

    php composer.phar update

Run the service:

    php -S 127.0.0.1:8080 -t public

A client-side AngularJS application will now be available by running

    grunt

The Grunt server will run at [http://localhost:9000](http://localhost:9000).  It will proxy REST requests to the Slim service running at [http://localhost:8080](http://localhost:8080).

At this point you should be able to navigate to a page to manage your persistent entities.

The Grunt server supports hot reloading of client-side HTML/CSS/Javascript file changes.
### Getting To Know Yeoman

Yeoman has a heart of gold. He's a person with feelings and opinions, but he's very easy to work with. If you think he's too opinionated, he can be easily convinced.

If you'd like to get to know Yeoman better and meet some of his friends, [Grunt](http://gruntjs.com) and [Bower](http://bower.io), check out the complete [Getting Started Guide](https://github.com/yeoman/yeoman/wiki/Getting-Started).


## License

MIT
