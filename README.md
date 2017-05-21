# bugreport

[![Build Status](https://travis-ci.org/robertbasic/bugreport.png?branch=master)](https://travis-ci.org/robertbasic/bugreport)

Get a bug report on a project's dependencies.

Looks at a GitHub repository and reports on:

 - number of open issues (done),
 - average age of open issues (done),
 - age of oldest open issue (done),
 - age of newest open issue (done),
 - number of open pull requests (done),
 - average age of open pull requests (done),
 - is the project deprecated?
 - did the project move?

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
