<?php

include dirname(__FILE__).'/../../bootstrap/functional.php';
include dirname(__FILE__).'/../../bootstrap/database.php';

$browser = new opTestFunctional(new sfBrowser());
$browser->login('html@example.com', 'password');

// CSRF
$browser
  ->info('/album/1055/photo/insert - CSRF')
  ->post('/album/1055/photo/insert')
  ->checkCSRF()

  ->info('/album/photo/update/1055 - CSRF')
  ->post('/album/photo/update/1055')
  ->checkCSRF()

  ->info('/album/photo/delete/1055 - CSRF')
  ->post('/album/photo/delete/1055')
  ->checkCSRF()

// XSS
  ->info('/album/1055/photo/insert - XSS')
  ->get('/album/1055/photo/insert')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Album', 'title')
  ->end()

  ->info('/album/photo/1055 - XSS')
  ->get('/album/photo/1055')
  ->with('html_escape')->begin()
    ->isAllEscapedData('AlbumImage', 'description')
  ->end()
;
