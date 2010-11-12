<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');
include(dirname(__FILE__).'/../../bootstrap/database.php');

$_app = 'pc_backend';
$browser = new opTestFunctional(new opBrowser(), new lime_test(null, new lime_output_color()));
$browser
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
