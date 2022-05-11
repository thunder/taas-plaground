#!/usr/bin/env bash

# Build the project
_task_build() {
    printf "Prepare composer.json\n\n"

    # Create project
    composer create-project "${TAAS_COMPOSER_PROJECT}":"${TAAS_COMPOSER_PROJECT_VERSION}" "${TAAS_INSTALLATION_DIRECTORY}" --no-interaction --no-install

    # Allow required plugins
    #composer config allow-plugins.cweagans/composer-patches true --no-plugins --working-dir="${TAAS_INSTALLATION_DIRECTORY}"
    #composer config allow-plugins.drupal/core-composer-scaffold true --no-plugins --working-dir="${TAAS_INSTALLATION_DIRECTORY}"
    #composer config allow-plugins.drupal/core-project-message true --no-plugins --working-dir="${TAAS_INSTALLATION_DIRECTORY}"
    #composer config allow-plugins.composer/installers true --no-plugins --working-dir="${TAAS_INSTALLATION_DIRECTORY}"
    #composer config allow-plugins.oomphinc/composer-installers-extender true --no-plugins --working-dir="${TAAS_INSTALLATION_DIRECTORY}"

    printf "Building the project.\n\n"

    # Install all dependencies
    cd "${TAAS_INSTALLATION_DIRECTORY}" || exit
    composer install

    # Add custom code
    if [[ -d "${TAAS_PROJECT_BASEDIR}"/modules/custom ]]; then
      ln -s "${TAAS_PROJECT_BASEDIR}"/modules/custom "${TAAS_DOCROOT}"/modules/custom
    elif [[ -d "${TAAS_PROJECT_BASEDIR}"/modules ]]; then
      ln -s "${TAAS_PROJECT_BASEDIR}"/modules "${TAAS_DOCROOT}"/modules/custom
    fi

    if [[ -d "${TAAS_PROJECT_BASEDIR}"/themes/custom ]]; then
      ln -s "${TAAS_PROJECT_BASEDIR}"/themes/custom "${TAAS_DOCROOT}"/themes/custom
    elif [[ -d "${TAAS_PROJECT_BASEDIR}"/themes ]]; then
      ln -s "${TAAS_PROJECT_BASEDIR}"/themes "${TAAS_DOCROOT}"/themes/custom
    fi

    # Back to previous directory.
    cd - || exit
}

_task_build_check_requirements() {
    printf "Check build requirements\n\n"

    if ! [[ -x "$(command -v composer)" ]]; then
        printf "composer not found, please install composer.\n"
        return
    fi
}
