<?php

include_once('SlugMaker.php');

/**
 * For command line input
 */
//$id = $argv[1];
//$name = $argv[2];


/**
 * For Browser input
 */
//$id = $_GET['id'];
//$name = $_GET['name'];


$id = 997;
$name = "John Cena";


$strClass = new StringConversion();
echo "'$name' with '$id' is converted  to :::::::::::::: ".$strClass->makeUserName($name, $id) . PHP_EOL;