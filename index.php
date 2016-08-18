<?php

if(!is_dir('installation')) {
  header('Location: web');
  header('X-Redirect: web');
  //require __DIR__ . '/web/index.php';
}else {
  require __DIR__ . '/installation/index.php';
}