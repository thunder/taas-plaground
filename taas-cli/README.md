# TaaS cli

Used to build the project, could be extended to do other CI tasks as well.

Until it can be properly required, call the taas binary in the root folder directly, or copy the build/taas.phar file.

It restricts the downloaded dependencies by comparing the enabled drupal modules (as defined in the custom core.extensions.yml)
with the list of optional modules (currently defined in the config/services.yml) and placing all optional modules, that
are not enabled, into the "replace" key of the build composer.json.

## Requirements

### PHIVE

[PHIVE](https://github.com/phar-io/phive) is needed to build the PHAR package.

    brew install phive

## Build project

call within your project root:

    taas build

To locally work on this project run

    composer install
    phive install --force-accept-unsigned

to create the phar run

    tools/box compile
