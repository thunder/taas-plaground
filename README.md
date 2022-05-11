# A testing playgroud to improve Thunder project maintenance
Contains several packages to test alternative installation of thunder projects.

The goal is, to be able to quickly test and verify ideas and concepts for the TaaS project. 

## Example Project (./example-project)
An example project, that uses a new project layout. It only contains custom code and config, but no dependencies.

## TaaS Dependencies (./taas-dependencies)
A project to create locked dependencies for all projects to use.

## TaaS CLI and Taas shell script (./taas-cli, ./taas-sh)
Two alternative implementations of TaaS build scripts. One writtem in shell script, based on drupal testing.
The other written as a symfony console app.

Both scripts can easily be extended to do other CI/CD steps as well (installing, testing)

I created multiple versions, to compare pros and cons.

## TaaS build project (./taas-build-project)
composer project to be used by build script, to combine TaaS dependencies with custom code. 

Each project has its own README.md with more details

