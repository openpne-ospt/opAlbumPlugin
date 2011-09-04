<?php

$app = 'mobile_frontend';
include dirname(__FILE__).'/../../bootstrap/functional.php';

$browser = new sfBrowser();
$user = new opTestFunctional($browser, new lime_test(3, new lime_output_color()));
$user->setMobile();

$user->login('html@example.com', 'password');

// CSRF
$user
  ->info('/album/photo/update/1055 - CSRF')
  ->post('/album/photo/update/1055')
  ->checkCSRF()

  ->info('/album/photo/delete/1055 - CSRF')
  ->post('/album/photo/delete/1055')
  ->checkCSRF()

// XSS
  ->info('/album/photo/1055 - XSS')
  ->get('/album/photo/1055')
  ->with('html_escape')->begin()
    ->isAllEscapedData('AlbumImage', 'description')
  ->end()
;
