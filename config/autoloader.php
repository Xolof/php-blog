<?php

spl_autoload_register(function ($class) {
  $parts = explode('\\', $class);
  include dirname(__DIR__) . "/src/" . implode($parts, DIRECTORY_SEPARATOR) . '.php';
});