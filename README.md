# GraphViz editor

[![Project Stage Badge: Production Ready]][Project Stage Page]
[![License Badge]][GPL-3.0]

## Introduction

A GraphViz editor. Can be seen live at: https://graphviz-editor.glitch.me/
No longer actively maintained.

## Installation

Requires Graphviz `dot` command [to be installed](https://graphviz.org/download/)

### Install instructions

To install this app yourself, you first need to clone the git repo.
If you clone the git repo on Github you should substitute the repo URL with your own.

    git clone https://github.com/potherca/GraphvizWebEditor.git
    cd GraphvizWebEditor

Now create an Heroku App, add the Multi Buildpack and deploy the code to Heroku:

    heroku apps:create my_awesome_app_name
    heroku buildpack:set https://github.com/heroku/heroku-buildpack-multi
    git add -u .
    git push heroku master


If you run into  problems with the `composer.lock` file, download Composer, run an
update and commit the lock file:

    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    composer update
    git add composer.lock

### Re-deploy to Heroku

To redeploy to Heroku, this trick might be handy:

    git push -f heroku master^:master
    git push heroku master

It pushes the previous version and then the current version to Heroku.

### Done!

This information should be good enough to get you up and running!

### Buildpacks

This app used the following buildpacks for Heroku:

- https://github.com/heroku/heroku-buildpack-php
- https://github.com/weibeld/heroku-buildpack-graphviz.git

These buildpacks will be automatically picked up if you set the heroku-buildpack-multi (as indicated above).

## Credits

- **Authors**:
    - Main code by [Potherca]
    - Bug fixes and several improvements gracefully provided by [Anne van Rossum]
- **License**: [GPL-3.0]

Want to contribute or see the project progress? Visit the [Waffle Page]!

[Anne van Rossum]: https://github.com/mrquincle
[compile script]: https://github.com/mrquincle/heroku-buildpack-graphviz/blob/master/bin/compile
[GPL-3.0]: ./LICENSE
[graphviz.herokuapp.com]: http://graphviz.herokuapp.com
[Potherca]: http://pother.ca/

[Project Stage Page]:   http://bl.ocks.org/potherca/a2ae67caa3863a299ba0
[Releases Page]:        /releases/

[License Badge]:            https://img.shields.io/badge/License-GPL--3.0-blue.svg
[Project Stage Badge: Production Ready]:   https://img.shields.io/badge/Project%20Stage-Production%20Ready-brightgreen.svg
[Version Badge]:            https://img.shields.io/github/tag/potherca/GraphvizWebEditor.svg
