# bugreport

[![Build Status](https://travis-ci.org/robertbasic/bugreport.png?branch=master)](https://travis-ci.org/robertbasic/bugreport)
[![Coverage Status](https://coveralls.io/repos/github/robertbasic/bugreport/badge.svg?branch=master)](https://coveralls.io/github/robertbasic/bugreport?branch=master)
[![Latest Stable Version](https://poser.pugx.org/robertbasic/bugreport/v/stable)](https://packagist.org/packages/robertbasic/bugreport)
[![License](https://poser.pugx.org/robertbasic/bugreport/license)](https://packagist.org/packages/robertbasic/bugreport)

Get a bug report on a project's dependencies.

Looks at a GitHub repository and reports on:

 - number of open issues,
 - average age of open issues,
 - age of oldest open issue,
 - age of newest open issue,
 - number of open pull requests,
 - average age of open pull requests.

Current version is: 0.0.1

## installation

Install it with composer as a `--dev` dependency:

```
composer require --dev robertbasic/bugreport:0.0.1
```

## usage

Running:

```
./bin/bugreport
```

will search for a `composer.lock` file in the current directory and go through
all the dependencies of the project.

To check for a single dependency, provide a `user/repository` combination:

```
./bin/bugreport user/repository
```

Passing in `--html` as a command line option, will tell `bugreport` to create
an HTML report, instead of a text one.

## configuration

By default `bugreport` will generate a `bugreport.txt` file in the current
working directory with the entire report. You can configure that by:

 - copy `bugreport.json.dist` to `bugreport.json`
 - add `bugreport.json` to `.gitignore`
 - edit `bugreport.json`, change the value of `bugreport_filename` to the path
 and filename where you want the report to be saved.

## github api rate limit

If you run `bugreport` too much, or against a project with lots of dependencies,
the github api rate limit might kick in. In that case, you need to create a
[GitHub personal access token](https://github.com/blog/1509-personal-api-tokens).

The **ONLY** scope `bugreport` requires is `public_repo`, nothing else.

Once you have the token do the following:

 - copy `bugreport.json.dist` to `bugreport.json`
 - add `bugreport.json` to `.gitignore`
 - edit `bugreport.json`, change the value of`github_personal_access_token` to
 your token.

## contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md)
for details on our code of conduct, and the process for submitting pull requests
to us.

## versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available,
see the [tags on this repository](https://github.com/robertbasic/bugreport/tags).

## todo

 - [ ] interactive configuration
 - [x] write report as html
 - [ ] phar for distribution
 - [ ] is the project deprecated?
 - [ ] did the project move?
 - [ ] number of contributors (regular vs. occasional)
 - [ ] age of last release
 - [ ] activity in the last 30 days (opened vs. closed issues/PRs)
 - [ ] get project name from composer.json

## authors

* **Robert Basic** - [robertbasic](https://github.com/robertbasic)

See also the list of [contributors](https://github.com/robertbasic/bugreport/contributors)
who participated in this project.

## license

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md)
file for details.
