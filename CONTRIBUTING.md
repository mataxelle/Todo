
# Contributing guide to the project

First of all, thank you for contributing to the project!

All types of contributions are encouraged and valued. See the [Table of Contents](#table-of-contents) for different ways to help and details about how this project handles them. Please make sure to read the relevant section before making your contribution. It will make it a lot easier for us maintainers and smooth out the experience for all involved. The community looks forward to your contributions.

> And if you like the project, but just don't have time to contribute, that's fine. There are other easy ways to support the project and show your appreciation, which we would also be very happy about:
> - Star the project
> - Tweet about it
> - Refer this project in your project's readme
> - Mention the project at local meetups and tell your friends/colleagues


## Table of Contents

- [I Have a Question](#i-have-a-question)
- [I Want To Contribute](#i-want-to-contribute)

## I Have a Question

Before you ask a question, it is best to search for existing [Issues](https://github.com/mataxelle/Todo/issues) that might help you. In case you have found a suitable issue and still need clarification, you can write your question in this issue. It is also advisable to search the internet for answers first.

If you then still feel the need to ask a question and need clarification, we recommend the following:

- Open an [Issue](https://github.com/mataxelle/Todo/issues/new).
- Provide as much context as you can about what you're running into.
- Provide project and platform versions (php, composer, etc), depending on what seems relevant.

We will then take care of the issue as soon as possible.

## I Want To Contribute

Before adding to the project, read the [README](README.md) section. Here are some resources to help you get started with open source contributions:

- [Finding ways to contribute to open source on GitHub](https://docs.github.com/en/get-started/exploring-projects-on-github/finding-ways-to-contribute-to-open-source-on-github)
- [Set up Git](https://docs.github.com/en/get-started/quickstart/set-up-git)
- [GitHub flow](https://docs.github.com/en/get-started/quickstart/github-flow)
- [Collaborating with pull requests](https://docs.github.com/en/github/collaborating-with-pull-requests)

### Your Code Contribution

### Your Code Contribution

1. [Fork the repo](https://docs.github.com/en/github/getting-started-with-github/fork-a-repo#fork-an-example-repository).
You can make your changes without affecting the original project until you're ready to merge.

2. Create a branch and start to make your changes.

3. Commit frequently on your working branch on your fork. However, in order to avoid conflicts, please also ensure that you git pull regularly too before pushing.

### Testing

In order for your tests to pass, you first need to create a test database using a .env.test.local file with your test
DATABASE_URL exactly like the production one.
Then, you need to load test fixtures.

To create your test database, run :

````
php bin/console doctrine:database:create --env=test
php bin/console doctrine:migration:migrate -n --env=test
php bin/console doctrine:fixtures:load --env=test
````

To run your test, run the following command :

````
php/bin/phpunit test--testdox
````

To run one specific test :

````
php bin/phpunit --filter=testShouldFinishTask 
````

To get the app's code coverage report, run the following command to generate in your var folder an HTML report which you may then view
in your web browser :

````
php bin/phpunit --coverage-html var/log/test/test-coverage
````

### Pull Request

When you're finished with the changes, the tests have passed, create a pull request, you may commit your changes.
Once you submit your changes, the team will review your proposal and will merge your work.