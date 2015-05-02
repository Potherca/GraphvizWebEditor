# GraphViz editor

[![Project Stage Badge: Production Ready]][Project Stage Page]
[![Dependency Badge]][VersionEye Page]
[![Codacy Badge]][Codacy Page]
[![Build Status Badge]][Codeship Page]
[![License Badge]][GPL-3.0]
[![Waffle Badge]][Waffle Page]

## Introduction

A GraphViz editor, which is live on [graphviz.herokuapp.com].

## Installation

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

[Codacy Page]:          https://www.codacy.com/public/potherca/GraphvizWebEditor.git
[Codeship Page]:        https://www.codeship.io/projects/34086
[Project Stage Page]:   http://bl.ocks.org/potherca/a2ae67caa3863a299ba0
[Releases Page]:        /releases/
[VersionEye Page]:      https://www.versioneye.com/user/projects/53fcf2bae09da3cbb2000717
[Waffle Page]:          https://waffle.io/potherca/GraphvizWebEditor

[Build Status Badge]:       https://img.shields.io/codeship/80020200-1764-0132-87c7-1e682cfc0f53.svg
[Codacy Badge]:             https://www.codacy.com/project/badge/588fcadde4084ddc91503a8d8da4afe1
[Dependency Badge]:         https://www.versioneye.com/user/projects/53fcf2bae09da3cbb2000717/badge.svg?style=flat
[License Badge]:            https://img.shields.io/badge/License-GPL--3.0-blue.svg
[Project Stage Badge: Production Ready]:   https://img.shields.io/badge/Project%20Stage-Production%20Ready-brightgreen.svg
[Version Badge]:            https://img.shields.io/github/tag/potherca/GraphvizWebEditor.svg
[Waffle Badge]:             https://badge.waffle.io/potherca/GraphvizWebEditor.png?label=waffle:%20ready%20for%20development&title=Issues%20ready%20for%20development
