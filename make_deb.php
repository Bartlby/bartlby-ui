<?php

require_once 'wdm/debian/Autoloader.php';

$control = new \wdm\debian\control\StandardFile();
$control
    ->setPackageName("bartlby-ui")
    ->setVersion("1.4.5")
    ->setDepends(array("php5", "php5-cli", "apache2"))
    ->setInstalledSize(4096)
    ->setMaintainer("Helmut Januschka", "helmut@januschka.com")
    ->setProvides("my-package-name")
    ->setDescription("My software description");
;

$packager = new \wdm\debian\Packager();

$packager->setOutputPath("/tmp/debian-ui/");
$packager->setControl($control);

$packager->mount("/storage/SF.NET/BARTLBY/GIT/bartlby-ui/", "/var/www/bartlby-ui");

//Creates folders using mount points
$packager->run();

//Creates the Debian package
$packager->build();
