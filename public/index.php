<?php

phpinfo();

// ImageMagick Test
// @unlink(__DIR__ . '/cache/national-park-wikimedia-commons-imagemagick-400x400.jpg');
// $image = new \Imagick(__DIR__ . '/testImages/national-park-wikimedia-commons.jpg');
// $image->resizeImage(400, 400, Imagick::COLOR_ALPHA, 1);
// $image->writeImage(__DIR__ . '/cache/national-park-wikimedia-commons-imagemagick-400x400.jpg');
// die;

// GD test
// $filename = __DIR__ . '/testImages/national-park-wikimedia-commons.jpg';
// $newFilename = __DIR__ . '/cache/national-park-wikimedia-commons-gd-400x400.jpg';
// @unlink($newFilename);
// $source = imagecreatefromjpeg($filename);
// list($width, $height) = getimagesize($filename);
// $newwidth = 400;
// $newheight = 400;
// $destination = imagecreatetruecolor(400, 400);
// imagecopyresampled($destination, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
// imagejpeg($destination, $newFilename, 100);
// die;

// var_dump(function_exists('gd_info'));

// var_dump(class_exists('Imagick'));

// $pdo = new PDO('mysql:host=db;port=3306;charset=utf8;dbname=site', 'site', 'secret');

// $createTable = $pdo->exec('CREATE TABLE IF NOT EXISTS testTable(ID INT( 11 ) AUTO_INCREMENT PRIMARY KEY, name VARCHAR(50))');

// $query = $pdo->query('SELECT * FROM testTable')->fetch();

// var_dump($createTable, $query);

// $link = mysqli_connect('db', 'site', 'secret', 'site', '3306');

// $link->query('CREATE TABLE IF NOT EXISTS testTable(ID INT( 11 ) AUTO_INCREMENT PRIMARY KEY, name VARCHAR(50))');

// $link->query("INSERT INTO testTable (name) VALUES ('Johnny')");

// var_dump($link->query("SELECT * FROM testTable"));

/**
 * Postegres
 */

// $pdo = new PDO('pgsql:host=db;port=5432', 'postgres', 'postgres');
//
// $pdo->exec('CREATE DATABASE site;');
// $pdo->exec("CREATE USER site WITH ENCRYPTED PASSWORD 'secret';");
// $pdo->exec('GRANT ALL PRIVILEGES ON DATABASE site TO site');
//
// var_dump($pdo);
// die;

///////////////////////////////////////

// $pdo = new PDO('pgsql:host=db;port=5432;dbname=site', 'site', 'secret');

// $createTable = $pdo->exec('CREATE TABLE test_table(ID serial PRIMARY KEY, name VARCHAR(50))');

// $pdo->exec("INSERT INTO test_table (name) VALUES ('Johnny')");

// $query = $pdo->query('SELECT ID, name FROM test_table')->fetch(PDO::FETCH_ASSOC);

// var_dump($query);
// die;
