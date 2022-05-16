#!/usr/bin/env php
<?php

writeComposerFile();

function writeComposerFile()
{
  $composerLockFile = json_decode(file_get_contents('assets/composer.lock'), true);
  $composerFileTemplate = json_decode(file_get_contents('assets/composer-template.json'), true);
  $buildComposerFile = 'composer.json';

  foreach ($composerLockFile['packages'] as $package) {
    $composerFileTemplate['require'][$package['name']] = $package['version'];
  }

  foreach ($composerLockFile['packages-dev'] as $package) {
    $composerFileTemplate['require-dev'][$package['name']] = $package['version'];
  }

  if (file_exists($buildComposerFile)) {
    unlink($buildComposerFile);
  }

  file_put_contents(
    'composer.json',
    json_encode($composerFileTemplate, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
  );
}
