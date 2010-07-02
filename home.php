<?php
error_reporting(0);

$url = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';

define('CONSUMER_KEY',    'y0Hwc6D11GKJ2G46Eovnw');
define('CONSUMER_SECRET', 'KrMGZ3Ip7YJYfu3gyJjbRaOcjbzBZ9OpbroqzzuuSjY');
define('OAUTH_CALLBACK',  'http://ayb.dgmike.com.br/callback');

include 'Oauth.php';
include 'controller.php';
include 'ice/app.php';

app(array(
  '^/update$'   => 'Update',
  '^/post$'     => 'Post',
  '^/main$'     => 'Main',
  // Authentication
  '^/callback$' => 'CallBack',
  '^/login$'    => 'Login',
  '^/logout$'   => 'Logout',
  // Home
  '^/?$'        => 'Home',
), $url);
