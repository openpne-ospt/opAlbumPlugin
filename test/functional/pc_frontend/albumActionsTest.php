<?php

include dirname(__FILE__).'/../../bootstrap/functional.php';
include dirname(__FILE__).'/../../bootstrap/database.php';

$browser = new opTestFunctional(new sfBrowser());
$browser->login('html@example.com', 'password');

// CSRF
$browser
  ->info('/album/create - CSRF')
  ->post('/album/create')
  ->checkCSRF()

  ->info('/album/update/1055 - CSRF')
  ->post('/album/update/1055')
  ->checkCSRF()

  ->info('/album/delete/1055 - CSRF')
  ->post('/album/delete/1055')
  ->checkCSRF()

// XSS
  ->info('/member/home - XSS')
  ->get('/member/home')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('Album', 'title')
  ->end()

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
  ->end()

  ->info('/album/1055 - XSS')
  ->get('/album/1055')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Album', 'title')
    ->countEscapedData(1, 'Album', 'body', array('width' => 36))
  ->end()

  ->login('sns@example.com', 'password')

  ->info('/member/1055 - XSS')
  ->get('/member/1055')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('Album', 'title')
  ->end()
;
