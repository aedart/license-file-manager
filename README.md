# License File Manager

A simple LICENSE-file manager for you composer packages. Allows you to store / organise your various licenses into a single package, fetch it and update your current project’s license file with the desired one. At its core, this package is nothing more than a "glorified" wrapper for PHP's copy function, but it makes managing license files a bit easier, when you are developing multiple packages.

# Contents

* [When to use this](#when-to-use-this)
* [How to install](#how-to-install)
* [Quick start](#quick-start)
* [License](#license)

## When to use this

How many composer-based packages are you working on? Do any of them contain a small file, named LICENSE? How do you manage that file, across all of your packages?

If you wish to maintain your license files from a single repository (or package) and just fetch the latest released version-file, then perhaps this small package might be able to help you.


## How to install

```console

composer require aedart/license-file-manager 1.*
```

This package uses [composer](https://getcomposer.org/). If you do not know what that is or how it works, I recommend that you read a little about, before attempting to use this package.

## Quick start

### Prerequisite

You should know how to work with [git-scm]( http://git-scm.com/)

You should have some knowledge of how to create, publish and work with [composer]( https://getcomposer.org/doc/04-schema.md) based projects

### Step 1: Create a repository (with a composer file)

Create a new repository that must contain your various license files.

Remember to also create a composer file for that package, with a custom name, e.g. `Acme/License`

If you need inspiration for how you could organise your files, please review the folder structure of [Aedart/License](https://github.com/aedart/license)

### Step 2: Create your license file

In your new repository, create a license file.

__Tip__: If you know that you are going to work with multiple types of licenses, then perhaps you should name each of your license-files according to their type, e.g. `Apache-2-license`

### Step 3: Publish your license repository / package

You can publish repository, once your license-file(s) are ready to be used in one of your projects.

However, please note that composer does also allow loading directly from repositories, if you do not wish to publish it on [Packagist](https://packagist.org). For additional information, review the [Repositories](https://getcomposer.org/doc/04-schema.md#repositories) section on composer’s documentation.

### Step 4: Require your license package and License Manager dependencies

In you require or require-dev, state your license package dependency, as well as this manager’s dependency;

```json
{
  "require-dev": {
    "acme/license": "1.*",
    "aedart/license-file-manager": "1.*"
  }
}
```

Run composer update…

__Tip__:  Unless there is a good reason why your entire license package should end up in the vendor folder, for those that are using it, then you should just require it for development – specify your dependency in the ‘require-dev’ section of your composer file.

### Step 5: Copy the desired license file

Invoke the following command, and your license file will be copied from your package into the root of your current project;

```shell
php vendor/bin/license-manager license:copy vendor/acme/license/Apache-2-license
```

A `LICENSE` file should be added into the root of your project.

__Warning__: If there already exists a file named LICENSE in your project root, then it will be overwritten!

#### Alternative: composer post-update-cmd script

If you wish to let composer do the work for you, each time that you work on your project, then you can specify a post update script, in your composer file;

```json
{
  "scripts": {
    "post-update-cmd": "vendor/bin/license-manager license:copy vendor/acme/license/Apache-2-license"
  }
}
```

Please note, this script will only be triggered when you are working on your package; thus, when it is being declared inside your root level composer file. Read more about composer’s [scripts]( https://getcomposer.org/doc/articles/scripts.md), in order to get a better understanding of how this works.

## Onward

This package offers you a way to organise your various license files into a single repository and fetch the one that you need. However, on the down side, your license file is only updated when you are working with your package. Nevertheless, it might still be a better way of managing license files, rather than manually having to copy, paste and maintain them across multiple packages.

## License

[BSD-3-Clause](http://spdx.org/licenses/BSD-3-Clause), Read the LICENSE file included in this package