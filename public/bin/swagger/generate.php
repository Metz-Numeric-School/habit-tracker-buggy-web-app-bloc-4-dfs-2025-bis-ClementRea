<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
use OpenApi\Generator;
$srcDirectories = [
    __DIR__ . '/../../../src',
    __DIR__ . '/../../../core'
  ];
$openapi = Generator::scan($srcDirectories);
$outputFile = __DIR__ . '/../../api-docs/swagger.json';
file_put_contents($outputFile, $openapi->toJson());
echo "Documentation Swagger générée avec succès dans : $outputFile\n";