PHPRelease
==========
[![Build Status](https://travis-ci.org/c9s/PHPRelease.png?branch=master)](https://travis-ci.org/c9s/PHPRelease)

The simplest way to define your release process.

Features
---------

- Automatically version bumping for Composer, Onion, PHPDoc or Class constant.
- Support version parsing from PHPDoc or class const.
- Git tagging, pushing.
- Simplest config.

Install
-------

```sh
$ curl -OLSs https://raw.github.com/c9s/PHPRelease/master/phprelease
$ chmod +x phprelease
$ mv phprelease /usr/bin
```

Usage
-----

Create phprelease.ini config file by a simple command:


```sh
$ phprelease init
```

The above command creates a `phprelease.ini` config file, you can also edit it
by yourself:

```ini
Steps = PHPUnit, BumpVersion, GitTag, GitPush, GitPushTags
```

The release steps may contains script files, you can simply insert the script
path and phprelease will run it for you. the return code 0 means we are going
to the next step.

```ini
Steps = BumpVersion, scripts/compile, GitTag

```

Then, to release your package, simply type:

```sh
$ phprelease
```

Bumping Version
---------------

To bump major version and do release:

    $ phprelease --bump-major
    ===> Bumping version from 2.2.3 => 3.0.0

To bump minor version and do release:

    $ phprelease --bump-minor
    ===> Bumping version from 2.2.3 => 2.3.0

To bump minor version and set the stability suffix:

    $ phprelease --bump-minor --dev
    ===> Bumping version from 2.2.3 => 2.3.0-dev

    $ phprelease --bump-minor --beta
    ===> Bumping version from 2.2.3 => 2.3.0-beta

    $ phprelease --bump-minor --rc
    ===> Bumping version from 2.2.3 => 2.3.0-rc

    $ phprelease --bump-minor --rc1
    ===> Bumping version from 2.2.3 => 2.3.0-rc1

    $ phprelease --bump-minor --rc2
    ===> Bumping version from 2.2.3 => 2.3.0-rc2

    $ phprelease --bump-minor --stable
    ===> Bumping version from 2.2.3 => 2.3.0

To use a version prefix for the git tag, add this key to your phprelease.ini:

```ini
GitTagPrefix = v.
```

This will result in something like:

    $ phprelease
    ===> Version bump from 2.2.3 to 2.3.0
    ===> Running PHPRelease\Tasks\GitTag
    ===> Tagging as v.1.2.2

Configuring GitAdd Task
------------------------

To use GitAdd Task, you may simply add the config below to your phprelease.ini:

```ini
[GitAdd]
Paths[] = src/
Paths[] = tests/
```

Skipping Specific Step
--------------------------

```sh
$ phprelease --skip BumpVersion
```

Getting Version From PHP Source File
-------------------------------------

If you defined your version string in your PHP source file or class const,
to bump version from php source file, you can simply define a `VersionFrom` option:


```ini
; to read version from php class file or from phpdoc "@VERSION ..."
VersionFrom = src/PHPRelease/Console.php
```


Task Options
------------

Each task has its own options, run help command, you should see the options from these tasks:

    $ phprelease help
    PHPRelease - The Fast PHP Release Manager

    Usage
        phprelease [options] [command] [argument1 argument2...]

    Options
               -v, --verbose   Print verbose message.
                 -d, --debug   Print debug message.
                 -q, --quiet   Be quiet.
                  -h, --help   help
                   --version   show version
                       --dry   dryrun mode.
                --bump-major   bump major (X) version.
                --bump-minor   bump minor (Y) version.
                --bump-patch   bump patch (Z) version, this is the default.
     -s, --stability <value>   set stability
                       --dev   set stability to dev.
                        --rc   set stability to rc.
                       --rc1   set stability to rc1.
                       --rc2   set stability to rc2.
                       --rc3   set stability to rc3.
                       --rc4   set stability to rc4.
                       --rc5   set stability to rc5.
                      --beta   set stability to beta.
                     --alpha   set stability to alpha.
                    --stable   set stability to stable.
           --remote <value>+   git remote names for pushing.


So to bump the major verion, simply pass the flag:

    phprelease --bump-major

You can also test your release steps in dry-run mode:

    phprelease --dryrun


Built-In Tasks
--------------

    BumpVersion
    GitCommit
    GitPush
    GitPushTags
    GitTag
    PHPUnit


Hacking
-------

1. For this project.

2. Get composer, and run:

    composer install --dev

3. Hack!
