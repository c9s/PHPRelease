PHPRelease
==========

phprelease manages your package release.

Features
---------

- Automatically version bumping for composer, onion, phpdoc or class const.
- Support Composer.
- Support Onion.
- Support version parsing from PHPDoc or class const.
- Git tagging.
- Simplest config.


Usage
-----

Create phprelease.ini config file:

```ini
Steps = BumpVersion, GitTag
```


The release steps may contains script files, you can simply insert the script path and 
phprelease will run it for you. the return code from the script 0 means we are 
going to the next step.

```ini
Steps = BumpVersion, scripts/compile, GitTag
```

Each task has its own options, run help command, you should see the options from these tasks:

    $ phprelease help
    Application brief

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

