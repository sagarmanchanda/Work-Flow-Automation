# Workflow Automation

[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]

API for workflow automation. Currently restricted to IITG campus, however can be extended to any work-place by hacking into the login and formbuilder tools.

# Dependencies

All dependencies are listed in the [DEPENDENCIES](DEPENDENCIES.md) file.

# Install

Installation recommended via Composer.

To install Composer:

``` bash
$ export HTTP_PROXY=[http proxy]
$ curl -sS https://getcomposer.org/installer | php
$ mv composer.phar /usr/local/bin/composer
```

``` Note: If the the above fails due to permissions, run the mv line again with sudo. ```

Now open the project directory and run:

``` bash
$ composer install
```
On successfull installation, a *vendors* folder will be generated.

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
$newform = new WFA\FormBuilder\Form();
```
# Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

# Testing

Testing will be done using PHPUnit and CI bot Jenkins. (Not implemented right now.)

# Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

# Issues

All issues are tracked through the GitLab issue tracker.

# License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Generate Documentation
```bash
$ ./phpdoc -d path/src/ -t path/docs/api/
```

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
