<?php

include dirname(__FILE__).'/../../../bootstrap/unit.php';
include dirname(__FILE__).'/../../../bootstrap/database.php';
sfContext::createInstance($configuration);
sfContext::getInstance()->getUser()->setMemberId(1);

$t = new lime_test(18, new lime_output_color());

$member1 = Doctrine::getTable('Member')->find(1);
$member2 = Doctrine::getTable('Member')->find(2);
$member3 = Doctrine::getTable('Member')->find(3);

$album1 = Doctrine::getTable('Album')->find(1);
$album2 = Doctrine::getTable('Album')->find(2);
$album3 = Doctrine::getTable('Album')->find(3);

$table = Doctrine::getTable('AlbumImage');
$albumImage1 = $table->find(1);
$albumImage2 = $table->find(2);
$albumImage3 = $table->find(3);

// getAlbumImagePager()
$result = $table->getAlbumImagePager();
$t->isa_ok($result, 'sfDoctrinePager', "->getAlbumImagePager() returns sfDoctrinePager's instance.");
$t->is($result->getNbResults(), 4, '->getAlbumImagePager() returns 4 items.');

$result = $table->getAlbumImagePager($album1);
$t->isa_ok($result, 'sfDoctrinePager', "->getAlbumImagePager() returns sfDoctrinePager's instance.");
$t->is($result->getNbResults(), 2, '->getAlbumImagePager() returns 2 items.');

$result = $table->getAlbumImagePager($album3);
$t->isa_ok($result, 'sfDoctrinePager', "->getAlbumImagePager() returns sfDoctrinePager's instance.");
$t->is($result->getNbResults(), 0, '->getAlbumImagePager() returns empty result.');

// getAlbumImagePagerForAlbum()
$result = $table->getAlbumImagePagerForAlbum($album1->id);
$t->isa_ok($result, 'sfDoctrinePager', "->getAlbumImagePagerForAlbum() returns sfDoctrinePager's instance.");
$t->is($result->getNbResults(), 2, '->getAlbumImagePagerForAlbum() returns 2 items.');

// getAlbumImageSearchPager()
$result = $table->getAlbumImageSearchPager(array('foo'));
$t->isa_ok($result, 'sfDoctrinePager', "->getAlbumImageSearchPager() returns sfDoctrinePager's instance.");
$t->is($result->getNbResults(), 3, '->getAlbumImageSearchPager() returns 3 items.');

$result = $table->getAlbumImageSearchPager(array("Nobody", "expects", "the", "Spanish", "Inquisition"));
$t->isa_ok($result, 'sfDoctrinePager', "->getAlbumImageSearchPager() returns sfDoctrinePager's instance.");
$t->is($result->getNbResults(), 0, '->getAlbumImageSearchPager() returns empty.');

// getPreviousAlbumImage()
$t->cmp_ok($table->getPreviousAlbumImage($albumImage1, 1), '===', false, '->getPreviousAlbumImage() returns false.');

$result = $table->getPreviousAlbumImage($albumImage2, 1);
$t->isa_ok($result, 'AlbumImage', "->getPreviousAlbumImage() returns AlbumImage's instance.");
$t->is($result->id, $albumImage1->id, '->getPreviousAlbumImage() returns previous AlbumImage corectlly.');

// getNextAlbumImage()
$result = $table->getNextAlbumImage($albumImage1, 1);
$t->isa_ok($result, 'AlbumImage', "->getNextAlbumImage() returns AlbumImage's instance.");
$t->is($result->id, $albumImage2->id, '->getNextAlbumImage() returns next AlbumImage corectlly.'); 

$t->cmp_ok($table->getNextAlbumImage($albumImage2, 1), '===', false, '->getNextAlbumImage() returns false.');
