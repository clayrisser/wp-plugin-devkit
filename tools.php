<?php

function main($argv) {
  $command = $argv[count($argv) - 1];
  switch($command) {
  case "init":
    init();
    break;
  default:
    write_ln("Invalid command", 1);
  }
}

function init() {
  $name = read_ln("Plugin Name: ");
  $author = read_ln("Plugin Author: ");
  write_ln("The plugin is called ".$name);
  write_ln("Your name is ".$author);
  name_plugin($name);
}

function name_plugin($name) {
  $snake = change_case($name, "snake");
  $kebab = change_case($name, "kebab");
  find_and_replace_all('plugin_name', $snake);
  find_and_replace_all('plugin-name', $kebab);
}

function change_case($str, $to, $from = "title") {
  $str = trim(preg_replace('/[\s\t\n\r\s]+/', " ", $str));
  if ($from == "title") {
    switch($to) {
    case "snake":
      preg_match_all('/(?<=\s)\w/', $str, $firstChars);
      preg_match_all('/\s\w/', $str, $matches);
      for($i = 0; $i < count($matches[0]); $i++) {
        $match = $matches[0][$i];
        $firstChar = "_".strtolower($firstChars[0][$i]);
        $str = str_replace($match, $firstChar, $str);
      }
      $str = lcfirst($str);
      break;
    case "kebab":
      $str = change_case($str, "snake");
      $str = str_replace("_", "-", $str);
      break;
    }
  }
  return $str;
}

function find_and_replace_all($find, $replace) {
  $files = recursively_get_files("./plugin-name");
  foreach($files as $path) {
    find_and_replace($path, $find, $replace);
  }
  find_and_replace(getcwd()."/Makefile", $find, $replace);
  find_and_replace(getcwd()."/composer.json", $find, $replace);
  find_and_replace(getcwd()."/README.md", $find, $replace);
}

function recursively_get_files($path) {
  $directory_iterator = new RecursiveDirectoryIterator($path);
  $iterator_iterator  = new RecursiveIteratorIterator($directory_iterator, RecursiveIteratorIterator::SELF_FIRST);
  $files = array();
  foreach ($iterator_iterator as $file) {
    $path = $file->getRealPath();
    if ($file->isFile()) {
      array_push($files, $path);
    }
  }
  return $files;
}

function find_and_replace($path, $find, $replace) {
  $file = file_get_contents($path);
  $file = str_replace($find, $replace, $file);
  file_put_contents($path, $file);
}

function write($message, $code = 0) {
  switch($code) {
  case 0:
    fwrite(STDOUT, $message);
    return $code;
  default:
    fwrite(STDERR, $message);
    return exit($code);
  }
}

function write_ln($message, $code = 0) {
  return write($message."\n", $code);
}

function read_ln($message) {
  if ($message) write($message);
  $stdin = fgets(STDIN);
  return substr($stdin, 0, strlen($stdin) - 1);
}

main($argv);
