#!/usr/bin/env bash

_task_install() {
    printf "Installing project\n\n"
    cd "${TAAS_INSTALLATION_DIRECTORY}" || exit

    local drush
    drush="composer exec -- drush --root=${TAAS_DOCROOT}"

    # Copy default settings and append config sync directory.
    local sites_directory="${TAAS_DOCROOT}/sites/${TAAS_SITES_DIRECTORY}"
    if ! [[ -f "${sites_directory}"/settings.php ]]; then
      cp "${TAAS_DOCROOT}"/sites/default/default.settings.php "${sites_directory}"/settings.php
      echo "\$settings['config_sync_directory'] = '${TAAS_CONFIG_SYNC_DIRECTORY}';" >>"${sites_directory}/settings.php"
    fi

    ${drush} --verbose --db-url="${TAAS_DATABASE_ENGINE}"://"${TAAS_DATABASE_USER}":"${TAAS_DATABASE_PASSWORD}"@"${TAAS_DATABASE_HOST}":"${TAAS_DATABASE_PORT}"/"${TAAS_DATABASE_NAME}" --sites-subdir="${TAAS_SITES_DIRECTORY}" --yes --existing-config site-install

    cd - || exit
}

_task_install_check_requirements() {
    printf "Check install requirements\n\n"

    if ! [[ -f "${TAAS_DOCROOT}"/index.php ]]; then
        printf "Site is not build, please run %s build.\n" "${0##*/}"
        return
    fi

    if ! [[ -x "$(command -v composer)" ]]; then
        printf "composer not found, please install composer.\n"
        return
    fi

    if ! port_is_open "${TAAS_DATABASE_HOST}" "${TAAS_DATABASE_PORT}"; then
        printf "Error: Database is not running, or configured incorrectly.\n"
        exit 1
    fi
}
