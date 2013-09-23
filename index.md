---
layout: layout
theme_dir: jekyll-theme

author: Kévin Gomez
title: Random-Stuff-Generator
description: API providing randomly generated entities (users, locations, events, ...)
project_url: https://github.com/K-Phoen/Random-Stuff-Generator
---

RandomStuff [![Build Status](https://travis-ci.org/K-Phoen/Random-Stuff-Generator.png?branch=master)](https://travis-ci.org/K-Phoen/Random-Stuff-Generator)
===========

Inspired by [Random User Generator](http://randomuser.me/), RandomStuff is a
Silex application generating random data to be used in your API.

It can currently generate random data for the following entities:

 * Users
 * Locations
 * Events

Check out the [live version](http://random-stuff.kevingomez.fr/)!

Installation
============

Clone this repository and install the project's dependencies:

    curl -s http://getcomposer.org/installer | php
    php composer.phar install

You're done! You can run the application using the PHP built-in webserver:

    php -S 0.0.0.0:4000 -t web/

Open `http://localhost:4000/` in your browser to see RandomStuff running.


Tests
=====

First, install the application as described in section [Installation](#installation).

Then run the testsuite:

    ./vendor/bin/phpunit


Credits
=======

As stated before, the concept of generating random data for API was taken from
[Random User Generator](http://randomuser.me/). In fact, even the current design
of the homepage is theirs (I really suck at integrating designs and I'm more
interested in what's behind it).


License
=======

RandomStuff is released under the MIT License. See the bundled LICENSE file for
details.
