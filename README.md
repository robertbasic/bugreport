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

To check for a single project:

```
bugreport user/repository
```

or for all of a project's dependencies (not yet implemented):

```
bugreport composer.json
```
