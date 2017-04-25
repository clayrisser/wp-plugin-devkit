<?php

function printLn($message) {
  fwrite(STDOUT, $message);
}

function readLn($message) {
  if ($message) printLn($message);
  return fgets(STDIN);
}

$name = readLn('Plugin Name: ');
$author = readLn('Plugin Author: ');

printLn('The plugin is called '.$name);
printLn('Your name is '.$author);
