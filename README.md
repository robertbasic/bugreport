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

will search for a `composer.lock` file in the current directory and go through all the dependencies of the project.

To check for a single dependency, provide a `user/repository` combination:

```
./bin/bugreport user/repository
```
