# contributing

We'd love you to help out with bugreport and no contribution is too small.

## reporting bugs

Issues can be reported on the [issue
tracker](https://github.com/robertbasic/bugreport/issues). Please try and report
any bugs with a minimal reproducible example, it will make things easier for
other contributors and your problems will hopefully be resolved quickly.

## requesting features

We're always interested to hear about your ideas and you can request features by
creating a ticket in the [issue tracker](https://github.com/robertbasic/bugreport/issues).
We can't always guarantee someone will jump on it straight away, but putting it
out there to see if anyone else is interested is a good idea.

Likewise, if a feature you would like is already listed in the issue tracker,
add a :+1: so that other contributors know it's a feature that would help others.

## contributing code and documentation

We loosely follow the
[PSR-1](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md)
and
[PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
coding standards, but we'll probably merge any code that looks close enough.

* Fork the [repository](https://github.com/robertbasic/bugreport) on GitHub
* Add the code for your feature or bug
* Add some tests for your feature or bug
* Optionally, but preferably, write some documentation
* Optionally, update the CHANGELOG.md file with your feature or
  [BC](http://en.wikipedia.org/wiki/Backward_compatibility) break
* Send a [Pull Request](https://help.github.com/articles/creating-a-pull-request)
  to the correct target branch (see below)

If you have a big change or would like to discuss something, create an issue in
the [issue tracker](https://github.com/robertbasic/bugreport/issues).

Any code you contribute must be licensed under the [MIT](http://opensource.org/licenses/MIT).

## Target Branch

bugreport may have several active branches at any one time and roughly follows a
[Git Branching Model](https://igor.io/2013/10/21/git-branching-model.html).
Generally, if you're developing a new feature, you want to be targeting the
master branch, if it's a bug fix, you want to be targeting a release branch.

## Testing bugreport

To run the unit tests for bugreport, clone the git repository, download Composer
using the instructions at
[http://getcomposer.org/download/](http://getcomposer.org/download/),
then install the dependencies with `php /path/to/composer.phar install`.

This will install the required dev dependencies and create the autoload files
required by the unit tests. You may run the `vendor/bin/phpunit` command
to run the unit tests. If everything goes to plan, there will be no failed tests!
