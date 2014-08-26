[![Stories in Ready](https://badge.waffle.io/potherca/GraphvizWebEditor.png?label=ready&title=Ready)](https://waffle.io/potherca/GraphvizWebEditor)
# GraphViz editor

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
    heroku config:set BUILDPACK_URL=https://github.com/ddollar/heroku-buildpack-multi.git
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

### Set Environment Variables

The environmental variables in the buildpack seems to be overwritten by the php 
one, or actually what happens, I don't care... They don't get exported... 

This means you have to set them yourself:

    heroku config:set PATH=/app/.heroku/php/bin:/app/.heroku/php/sbin:/app/.heroku/graphviz/bin:/usr/local/bin:/usr/bin:/bin
    heroku config:set LD_LIBRARY_PATH=/app/.heroku/graphviz/lib

Use `heroku run bash` to check if `echo $PATH` has other variables and adjust accordingly.

### Done!

This information should be good enough to get you up and running!

### Buildpacks

This app used the following buildpacks for Heroku:
    
- https://github.com/heroku/heroku-buildpack-php
- https://github.com/mrquincle/heroku-buildpack-clearcache
- https://github.com/mrquincle/heroku-buildpack-graphviz

These buildpacks will be automatically picked up if you set the `BUILDPACK_URL` 
to `heroku-buildpack-multi` (as as indicated below). 

Note that the graphviz and clearcache buildpacks are slightly different from the 
originals. You can check the source-code of the [compile script] to see the 
changes. 

An important change was `--disable-perl` for the `configure` script, or else the 
installation breaks.

Currently the `CACHE_DIR` is emptied by the top-line in the `.buildpacks` file:

    https://github.com/mrquincle/heroku-buildpack-clearcache

If everything runs fine, it is safe to remove this line. This will allow you to 
cache the build results over different deployments on Heroku.


## Credits

- **Authors**:
    - Main code by [Potherca] 
    - Bug fixes and several improvements gracefully provided by [Anne van Rossum]
- **License**: [GPL3]
- **Date**: 2013-2014

[graphviz.herokuapp.com]: http://graphviz.herokuapp.com
[compile script]: https://github.com/mrquincle/heroku-buildpack-graphviz/blob/master/bin/compile
[GPL3]: ./LICENSE
[Potherca]: http://pother.ca/
[Anne van Rossum]: https://github.com/mrquincle
