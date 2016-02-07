# Contributing

Contributions are **welcome** and will be fully **credited**.

We accept contributions via Merge Requests.

## Getting Started

Start off by forking the repository and then cloning it in your machine.

``` bash
$ git clone https://gitlab.com/3S1J/team13cs243.git
```

After that, add the required urls to git.

``` bash
$ git remote add upstream https://gitlab.com/3S1J/team13cs243.git
$ git remote add origin https://gitlab.com/:username/team13cs243.git
```

## Merge Requests

After saving your changes, commit them to your local repository with the following command:

``` bash
$ git commit -a
```
``` Note: In case you want to amend your commit, use $ git commit -a --amend ```

Then push the changes to your forked repository using:

``` bash
$ git push origin master
```
Go to the *Merge Requests* section in GitLab and create a new merge request for review.

## Running Tests

``` bash
$ composer test
```


**Happy coding**!
