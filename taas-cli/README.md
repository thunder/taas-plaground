# TaaS cli

Currently used to build the project, could be extended to do other CI stuff as well.

Until it can be properly required, call the taas binary in the root folder directly, or copy the build/taas.phar file.

It restricts the downloaded dependencies by comparing the enabled drupal modules (as defined in the custom core.extensions.yml)
with the list of optional modules (currently defined in the config/services.yml) and placing all optional modules, that
are not enabled, into the "replace" key of the build composer.json.

## Build project

call withgin your project root:

    taas build

To localy work on this project run

    composer install
    phive install

to create the phar run

    tools/box compile
