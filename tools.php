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
  $plugin_name = read_ln("Plugin Name (Plugin Devkit): ");
  $author = read_ln("Plugin Author (Jam Risser): ");
  $contributors = read_ln("Contributors (jamrizzi): ");
  $tags = read_ln("Tags (comments, spam): ");
  $description = read_ln("Description (This is a short description of what the plugin does. It's displayed in the WordPress admin area.): ");
  $version = read_ln("Version (0.0.1): ");
  $requires = read_ln("Requires (3.0.1): ");
  $tested = read_ln("Tested (3.4): ");
  $stable = read_ln("Stable (4.3): ");
  $license = read_ln("License (GPL-3.0+): ");
  $plugin_uri = read_ln("Plugin URI (https://wordpress.org/plugins/plugin-devkit/): ");
  $author_uri = read_ln("Author URI (https://jamrizzi.com/): ");
  $license_uri = read_ln("License URI (http://www.gnu.org/licenses/gpl-3.0.html): ");
  $donate_link = read_ln("Donate link (https://jamrizzi.com/#!/buy-me-coffee): ");
  name_plugin($plugin_name);
  find_and_replace_all("Jam Risser", $author);
  find_and_replace_all("jamrizzi", $contributors);
  find_and_replace_all("comments, spam", $tags);
  find_and_replace_all("This is a short description of what the plugin does. It's displayed in the WordPress admin area.", $description);
  find_and_replace_all("0.0.1", $version);
  find_and_replace_all("3.0.1", $requires);
  find_and_replace_all("3.4", $tested);
  find_and_replace_all("4.3", $stable);
  find_and_replace_all("GPL-3.0+", $license);
  find_and_replace_all("https://wordpress.org/plugins/plugin-devkit/", $plugin_uri);
  find_and_replace_all("https://jamrizzi.com/", $author_uri);
  find_and_replace_all("http://www.gnu.org/licenses/gpl-3.0.html", $license_uri);
  find_and_replace_all("https://jamrizzi.com/#!/buy-me-coffee", $donate_link);
}

function name_plugin($name) {
  $snake = change_case($name, "snake");
  $kebab = change_case($name, "kebab");
  $space = change_case($name, "space");
  $cap_snake = change_case($name, "cap_snake");
  $cap_kebab = change_case($name, "cap_kebab");
  $cap_space = change_case($name, "cap_space");
  find_and_replace_all('plugin_devkit', $snake);
  find_and_replace_all('plugin-devkit', $kebab);
  find_and_replace_all('plugin devkit', $space);
  find_and_replace_all('Plugin_Devkit', $cap_snake);
  find_and_replace_all('Plugin-Devkit', $cap_kebab);
  find_and_replace_all('Plugin Devkit', $cap_space);
  find_and_replace_files('plugin_devkit', $snake);
  find_and_replace_files('plugin-devkit', $kebab);
  find_and_replace_files('Plugin_Devkit', $cap_snake, $kebab);
  find_and_replace_files('Plugin-Devkit', $cap_kebab, $kebab);
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
      $str = strtolower($str);
      break;
    case "kebab":
      $str = change_case($str, "snake");
      $str = str_replace("_", "-", $str);
      break;
    case "space":
      $str = change_case($str, "snake");
      $str = str_replace("_", " ", $str);
      break;
    case "cap_snake":
      $str = change_case($str, "cap_space");
      $str = str_replace(" ", "_", $str);
      break;
    case "cap_kebab":
      $str = change_case($str, "cap_space");
      $str = str_replace(" ", "-", $str);
      break;
    case "cap_space":
      $str = change_case($str, "space");
      $str = ucwords($str);
      break;
    }
  }
  return $str;
}

function find_and_replace_all($find, $replace) {
  $files = recursively_get_files("./plugin-devkit");
  foreach($files as $path) {
    find_and_replace($path, $find, $replace);
  }
  find_and_replace(getcwd()."/Makefile", $find, $replace);
  find_and_replace(getcwd()."/composer.json", $find, $replace);
  find_and_replace(getcwd()."/README.md", $find, $replace);
  find_and_replace(getcwd()."/tools.php", $find, $replace);
}

function find_and_replace_files($find, $replace, $root = "plugin-devkit") {
  $files = recursively_get_files("./".$root);
  foreach($files as $path) {
    if (strpos($path, $find)) {
      preg_match_all('/\/[\w\d-_\.]+$/', $path, $matches);
      if (count($matches[0]) > 0) {
        $to_find = $matches[0][0];
        $to_replace = str_replace($find, $replace, $to_find);
        $new_path = str_replace($to_find, $to_replace, $path);
        rename($path, $new_path);
      }
    }
  }
  if (file_exists(getcwd()."/".$find)) {
    rename(getcwd()."/".$find, getcwd()."/".$replace);
  }
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
