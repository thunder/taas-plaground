# TaaS cli

Currently used to build the project, could be extended to do other CI stuff as well.

until it can be properly required, call the taas binary in the root folder directly, or copy the build/taas.phar file 

## Build project

call withgin your project root:

    taas build

To localy work on this project run

    composer install
    phive install

to create the phar run

    tools/box compile