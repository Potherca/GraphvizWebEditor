# GraphViz editor

A GraphViz editor, which is live on [graphviz.herokuapp.com](http://graphviz.herokuapp.com) uses the following buildpacks:
    
    https://github.com/heroku/heroku-buildpack-php
    https://github.com/mrquincle/heroku-buildpack-graphviz

This is not to worry about, they will be automatically picked up if you set the `heroku-buildpack-multi` as `BUILDPACK_URL` as indicated below.

To install:

    heroku apps:create invent_name
    heroku config:set BUILDPACK_URL=https://github.com/ddollar/heroku-buildpack-multi.git
    git add -u .
    git push heroku master

Perhaps there are some problems with the composer.lock file. In that case download the composer and run it.

    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    composer update
    git add composer.lock



## Copyrights

* Author: Potherca

