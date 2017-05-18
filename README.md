# bugreport

Get a bug report on a project's dependencies.

Looks at a GitHub repository and reports on:

 - number of open issues,
 - number of closed issues,
 - average age of open issues,
 - age of oldest open issue,
 - age of newest open issue,
 - number of open pull requests,
 - average age of open pull requests
 - is the project deprecated?
 - did the project move?

## usage

To check for a single project:

```
bugreport user/repository
```

or for all of a project's dependencies:

```
bugreport composer.json
```
