<?php

include dirname(__FILE__).'/../../../bootstrap/unit.php';
include dirname(__FILE__).'/../../../bootstrap/database.php';
sfContext::createInstance($configuration);
sfContext::getInstance()->getUser()->setMemberId(1);

$t = new lime_test(28, new lime_output_color());

$member1 = Doctrine::getTable('Member')->find(1);
$member2 = Doctrine::getTable('Member')->find(2);
$member3 = Doctrine::getTable('Member')->find(3);

$table = Doctrine::getTable('Album');
$album1 = $table->find(1);
$album2 = $table->find(2);
$album3 = $table->find(3);

// getPublicFlags()
$t->is($table->getPublicFlags(), array (
  1 => '全員に公開',
  2 => 'マイフレンドまで公開',
  3 => '公開しない',
), '->getPublicFlags() returns labels of public flag.');

// getAlbumList()
$result = $table->getAlbumList();
$t->isa_ok($result, 'Doctrine_Collection', "->getAlbumList() returns Doctrine_Collection's instance.");
$t->is($result->count(), 4, "->getAlbumList() returns 4 items.");

// getAlbumPager()
$result = $table->getAlbumPager();
$t->isa_ok($result, 'sfDoctrinePager', "->getAlbumPager() returns sfDoctrinePager's instance.");
$t->is($result->getNbResults(), 4, "->getAlbumPager() returns 4 items.");

// getAlbumSearchPager()
$result = $table->getAlbumSearchPager(array("member1"));
$t->isa_ok($result, 'sfDoctrinePager', "->getAlbumSearchPager() returns sfDoctrinePager's instance.");
$t->is($result->getNbResults(), 1, "->getAlbumSearchPager() returns 1 items.");

$result = $table->getAlbumSearchPager(array("Nobody", "expects", "the", "Spanish", "Inquisition"));
$t->isa_ok($result, 'sfDoctrinePager', "->getAlbumSearchPager() returns sfDoctrinePager's instance.");
$t->is($result->getNbResults(), 0, "->getAlbumSearchPager() returns empty collection.");

// getMemberAlbumList()
$result = $table->getMemberAlbumList(1);
$t->isa_ok($result, 'Doctrine_Collection', "->getMemberAlbumList() returns Doctrine_Collection's instance.");
$t->is($result->count(), 3, "->getMemberAlbumList() returns 3 items.");

$result = $table->getMemberAlbumList(1, 5, 2);
$t->isa_ok($result, 'Doctrine_Collection', "->getMemberAlbumList() returns Doctrine_Collection's instance.");
$t->is($result->count(), 2, "->getMemberAlbumList() returns 2 items.");

$result = $table->getMemberAlbumList(1, 5, 3);
$t->isa_ok($result, 'Doctrine_Collection', "->getMemberAlbumList() returns Doctrine_Collection's instance.");
$t->is($result->count(), 1, "->getMemberAlbumList() returns 1 items.");

// getMemberAlbumPager()
$result = $table->getMemberAlbumPager(1, 1, 20);
$t->isa_ok($result, 'sfDoctrinePager', "->getMemberAlbumPager() returns sfDoctrinePager's instance.");
$t->is($result->getNbResults(), 3, "->getMemberAlbumPager() returns 3 items.");

// getFriendAlbumList()
$result = $table->getFriendAlbumList(1);
$t->isa_ok($result, 'Doctrine_Collection', "->getFriendAlbumList() returns Doctrine_Collection's instance.");
$t->is($result->count(), 1, "->getFriendAlbumList() returns 1 items.");

// getFriendAlbumPager()
$result = $table->getFriendAlbumPager(1);
$t->isa_ok($result, 'sfDoctrinePager', "->getFriendAlbumPager() returns sfDoctrinePager's instance.");
$t->is($result->getNbResults(), 1, "->getFriendAlbumPager() returns 1 items.");

// getPreviousAlbum()
$result = $table->getPreviousAlbum($album2, 1);
$t->isa_ok($result, 'Album', "->getPreviousAlbum() returns Album's instance.");
$t->is($result->id, $album1->id, "->getPreviousAlbum() returns previous album corectlly.");

$result = $table->getPreviousAlbum($album1, 1);
$t->cmp_ok($result, '===', false, '->getPreviousAlbum() returns false.');

// getNextAlbum()
$result = $table->getNextAlbum($album2, 1);
$t->isa_ok($result, 'Album', "->getNextAlbum() returns Album's instance.");
$t->is($result->id, $album3->id, "->getNextAlbum() returns previous album corectlly.");

$result = $table->getNextAlbum($album3, 1);
$t->cmp_ok($result, '===', false, '->getPreviousAlbum() returns false.');

// getMemberAlbumTitleArray()
$result = $table->getMemberAlbumTitleArray(1);
$t->is($result, array (
  1 => 'Member1\'s-album1',
  2 => 'Member1\'s-album2',
  3 => 'Member1\'s-album3',
), "->getMemberAlbumTitleArray() returns array of the album's title.");
