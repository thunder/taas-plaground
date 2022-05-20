#!/usr/bin/env php
<?php

writeComposerFile();

function writeComposerFile()
{

  $version = 1;

  $composerLockFile = json_decode(file_get_contents('assets/composer.lock'), true);
  $satisFileTemplate = json_decode(file_get_contents('assets/satis-template.json'), true);
  $buildSatisFile = 'satis.json';

  foreach ($composerLockFile['packages'] as $package) {
    $satisFileTemplate['require'][$package['name']] = $package['version'];
  }

  foreach ($composerLockFile['packages-dev'] as $package) {
    $satisFileTemplate['require'][$package['name']] = $package['version'];
  }

  $satisFileTemplate['homepage'] .=  "/$version";
  $satisFileTemplate['output-dir'] .=  "/$version";

  if (file_exists($buildSatisFile)) {
    unlink($buildSatisFile);
  }

  file_put_contents(
    $buildSatisFile,
    json_encode($satisFileTemplate, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
  );
}
