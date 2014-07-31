# GraphViz editor

A GraphViz editor, live on [graphviz.herokuapp.com](http://graphviz.herokuapp.com).
    
    heroku config:add BUILDPACK_URL=https://github.com/jeluard/heroku-buildpack-graphviz.git

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

