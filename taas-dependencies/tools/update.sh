#!/usr/bin/env bash

cd assets || exit
composer update
cd ..

tools/write-composer-file.php
