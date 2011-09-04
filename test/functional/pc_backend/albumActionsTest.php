<?php

$app = 'pc_backend';
include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new opBrowser();
$user = new opTestFunctional($browser, new lime_test(8, new lime_output_color()));
$user
  ->info('Login')
  ->get('/default/login')
  ->click('ログイン', array('admin_user' => array(
    'username' => 'admin',
    'password' => 'password',
  )))
  ->isStatusCode(302)

// CSRF
  ->info('/monitoring/album/delete/1055 - CSRF')
  ->post('/monitoring/album/delete/1055')
  ->checkCSRF()

// XSS
  ->info('/monitoring/album/search - XSS')
  ->get('/monitoring/album/search', array('keyword' => 'Album.body'))
  ->with('html_escape')->begin()
    ->isAllEscapedData('Album', 'title')
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('Album', 'body')
  ->end()

  ->info('/monitoring/album/deleteConfirm/1055 - XSS')
  ->get('/monitoring/album/deleteConfirm/1055')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Album', 'title')
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('Album', 'body')
  ->end()
;
