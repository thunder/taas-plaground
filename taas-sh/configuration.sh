#!/usr/bin/env bash
# Use this file as reference on what you can configure. You can set each of these variables in the environment
# to override the default values.

# Generate more verbose output, defaults to false. Can also be set to true by providing the -v parameter to the invoking command.
TAAS_VERBOSE=${TAAS_VERBOSE:-false}

# The directory, where the project is located. On travis this is set to TRAVIS_BUILD_DIR otherwise defaults to the current directory
TAAS_PROJECT_BASEDIR=${TAAS_PROJECT_BASEDIR:-$(pwd)}

# The composer project to use. defaults to the drupal/recommended-project. But e.g. Distribution specific projects can be used instead.
TAAS_COMPOSER_PROJECT=${TAAS_COMPOSER_PROJECT:-"thunder/thunder-project"}

# The version of the composer project to use.
TAAS_COMPOSER_PROJECT_VERSION=${TAAS_COMPOSER_PROJECT_VERSION:-"*"}

# The directory, where drupal will be installed, defaults to ${TAAS_TEST_BASE_DIRECTORY}/install
TAAS_INSTALLATION_DIRECTORY=${TAAS_INSTALLATION_DIRECTORY:-${TAAS_PROJECT_BASEDIR}/install}

# The docroot, defaults to ${TAAS_INSTALLATION_DIRECTORY}/docroot
TAAS_DOCROOT=${TAAS_DOCROOT:-${TAAS_INSTALLATION_DIRECTORY}/docroot}

# Sites directory used for installation.
TAAS_SITES_DIRECTORY=${TAAS_SITES_DIRECTORY:-default}

# The directory where the configuration for the installation with existing config is located relative to docroot.
TAAS_CONFIG_SYNC_DIRECTORY=${TAAS_CONFIG_SYNC_DIRECTORY:-"../../config/sync"}

# The database host.
TAAS_DATABASE_HOST=${TAAS_DATABASE_HOST:-""}

# The database port. Defaults to 3306.
TAAS_DATABASE_PORT=${TAAS_DATABASE_PORT:-3306}

# The database user.
TAAS_DATABASE_USER=${TAAS_DATABASE_USER:-""}

# The database name.
TAAS_DATABASE_NAME=${TAAS_DATABASE_NAME:-""}

# The database password for ${TAAS_DATABASE_USER}.
TAAS_DATABASE_PASSWORD=${TAAS_DATABASE_PASSWORD:-""}

# The database engine to use. Could be mysql or postgres. Defaults to mysql.
TAAS_DATABASE_ENGINE=${TAAS_DATABASE_ENGINE:-"mysql"}
