<?php

$app = 'pc_backend';
include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new opTestFunctional(new opBrowser(), new lime_test(8, new lime_output_color()));
$browser
  ->info('Login')
  ->get('/default/login')
  ->click('ログイン', array('admin_user' => array(
    'username' => 'admin',
    'password' => 'password',
  )))
  ->isStatusCode(302)

// CSRF
  ->info('/monitoring/album/image/delete/1055 - CSRF')
  ->post('/monitoring/album/image/delete/1055')
  ->checkCSRF()

// XSS
  ->info('/monitoring/album/image/search - XSS')
  ->get('/monitoring/album/image/search', array('keyword' => 'AlbumImage.description'))
  ->with('html_escape')->begin()
    ->isAllEscapedData('Album', 'title')
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('AlbumImage', 'description')
  ->end()

  ->info('/monitoring/album/image/deleteConfirm/1055 - XSS')
  ->get('/monitoring/album/image/deleteConfirm/1055', array('keyword' => 'AlbumImage.description'))
  ->with('html_escape')->begin()
    ->isAllEscapedData('Album', 'title')
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('AlbumImage', 'description')
  ->end()
;
