# TaaS Dependencies

This project is used to maintain the composer.json file, that requires all locked TaaS dependencies.

This composer.json is used to build any TaaS project and makes sure, that the exact same versions of everything is
used everywhere.

The assets folder contains the composer.json file, that has non-strict min-versions of the dependencies. It is used
to update the composer.lock file from which the locked versions are extracted. It has to contain all dependencies,
that are possible to use in projects.

# Update composer.json file

To update the composer.json file in th root directory run tools/update.sh from within the projects root folder.

# Add new dependencies

To add new dependencies, add them to the assets/composer.json file and run tools/update.sh
