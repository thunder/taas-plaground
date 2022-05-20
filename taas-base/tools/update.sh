#!/usr/bin/env bash

cd assets || exit
composer update
cd ..

tools/write-satis-file.php
