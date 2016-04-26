# Workflow Automation

[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]

API for workflow automation. Currently restricted to IIT Guwahati campus, however can be extended to any work-place by hacking into the authentication and formbuilder tools.

Every updated detail is available in the [Wiki](https://gitlab.com/3S1J/team13cs243/wikis/home)

# Install

To install, download the WFA package from GitLab and run ```install.sh```:
```bash
$ . install.sh
```

Note: if it does not work, try the following and re-run:
```bash
$ sudo chmod a+x install.sh
```

# Usage

The directory structure within *src* folder and the WFA namespace structure is linked.

## Using Form Builder

To start building a form:
``` php
require 'vendor/autoload.php';
$form = new WFA\FormBuilder\Form();
```

Currently supported input types include textboxes, radio buttons, password fields and submit buttons. To add new elements to form:
``` php
$form->addElement('type of input', 'name of field', 'HTML label for the field');
```

To add validation rules like *email* or *required* to these fields:
``` php
$form->addElement('type of input', 'name of field', 'HTML label for the field');
$form->addRule('name of field', 'required');
$form->addRule('name of field', 'email');
```
After you're done adding form elements, close the form by the following:
``` php
$form->buildForm();
```

Now you may proceed to add a new form, for example:
``` php
$form2 = new WFA\FormBuilder\Form();
```

# Generate Documentation (For Developers)
Add `phpdoc` path to your environment variables:

```bash
$ cd vendor/phpdocumentor/phpdocumentor/bin
$ export PATH=$PWD:$PATH
```

Then go back to the root of the software and execute the following:

```bash
$ phpdoc -d src/ -t docs/
```

# Dependencies

All dependencies are listed in the [DEPENDENCIES](DEPENDENCIES.md) file.

# Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

# Testing

Testing is done through PHPUnit. All test suites are stored in the 'tests' folder. All tests for a class 'Class' are named as 'ClassTest'.
To run tests for a class say 'ExampleClass', use the following:

```bash
$ phpunit --bootstrap vendor/autoload.php tests/ExampleClassTest
```

# Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

# Issues

All issues are tracked through the GitLab issue tracker.

# License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/:vendor/:package_name.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/:vendor/:package_name/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/:vendor/:package_name.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/:vendor/:package_name.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/:vendor/:package_name.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/:vendor/:package_name
[link-travis]: https://travis-ci.org/:vendor/:package_name
[link-scrutinizer]: https://scrutinizer-ci.com/g/:vendor/:package_name/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/:vendor/:package_name
[link-downloads]: https://packagist.org/packages/:vendor/:package_name
[link-author]: https://github.com/:author_username
[link-contributors]: ../../contributors
