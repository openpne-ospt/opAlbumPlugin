<?php

$app = 'mobile_frontend';
include dirname(__FILE__).'/../../bootstrap/functional.php';

$browser = new opBrowser();
$user = new opTestFunctional($browser, new lime_test(9, new lime_output_color()));
$user->setMobile();

$user->login('html@example.com', 'password');

// CSRF
$user
  ->info('/album/update/1055 - CSRF')
  ->post('/album/update/1055')
  ->checkCSRF()

  ->info('/album/delete/1055 - CSRF')
  ->post('/album/delete/1055')
  ->checkCSRF()

// XSS
  ->info('/album - XSS')
  ->get('/album')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->countEscapedData(2, 'Album', 'title', array('width' => 36))
    ->countEscapedData(2, 'Album', 'body', array('width' => 36))
  ->end()

  ->info('/album/listFriend - XSS')
  ->get('/album/listFriend')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Album', 'title')
    ->countEscapedData(1, 'Album', 'body', array('width' => 36))
  ->end()

  ->info('/album/listMember - XSS')
  ->get('/album/listMember')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Album', 'title')
    ->countEscapedData(1, 'Album', 'body', array('width' => 36))
  ->end();
