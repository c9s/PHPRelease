PHPRelease
==========

phprelease manages your package release process.

Features
---------

- Automatically version bumping for composer, onion, phpdoc or class const.
- Support Composer.
- Support Onion.
- Support version parsing from PHPDoc or class const.
- Git tagging, pushing.
- Simplest config.

Install
-------

```sh
$ curl -O https://raw.github.com/c9s/PHPRelease/master/phprelease
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
Steps = BumpVersion, GitTag
```


The release steps may contains script files, you can simply insert the script path and 
phprelease will run it for you. the return code from the script 0 means we are 
going to the next step.

```ini
Steps = BumpVersion, scripts/compile, GitTag

; to read version from php class file or from phpdoc "@VERSION ..."
VersionFrom = src/PHPRelease/Console.php
```

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
       --remote <value>+   git remote names for pushing.


So to bump the major verion, simply pass the flag:

    phprelease --bump-major

You can also test your release steps in dry-run mode:

    phprelease --dryrun



Hacking
-------

1. For this project.

2. Get composer, and run:

    composer install --dev

3. Hack!


