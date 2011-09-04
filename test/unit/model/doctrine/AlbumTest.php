<?php

include dirname(__FILE__).'/../../../bootstrap/unit.php';
include dirname(__FILE__).'/../../../bootstrap/database.php';
sfContext::createInstance($configuration);
sfContext::getInstance()->getUser()->setMemberId(1);

$t = new lime_test(28, new lime_output_color());

$member1 = Doctrine::getTable('Member')->find(1);
$member2 = Doctrine::getTable('Member')->find(2);
$member3 = Doctrine::getTable('Member')->find(3);
$album1 = Doctrine::getTable('Album')->find(1);
$album2 = Doctrine::getTable('Album')->find(2);
$album3 = Doctrine::getTable('Album')->find(3);

// getPublicFlagLabel()
$t->is($album1->getPublicFlagLabel(), '全員に公開', '->getPublicFlagLabel() returns a label correctly when public_flag is 1.');
$t->is($album2->getPublicFlagLabel(), 'マイフレンドまで公開', '->getPublicFlagLabel() returns a label correctly when public_flag is 2');
$t->is($album3->getPublicFlagLabel(), '公開しない', '->getPublicFlagLabel() returns a label correctly when public_flag is 3');

// getPrevious()
$t->cmp_ok($album1->getPrevious(), '===', false, '->getPrevious() returns false.');
$album2_p = $album2->getPrevious();
$t->isa_ok($album2_p, 'Album', "->getPrevious() returns Album's instance.");
$t->is($album2_p->id, $album1->id, '->getPrevious() returns previous album.');

// getNext()
$album1_n = $album1->getNext();
$t->isa_ok($album1_n, 'Album', "->getNext() returns Album's instance.");
$t->is($album1_n->id, $album2->id, '->getNext() returns next album.');

$t->cmp_ok($album3->getNext(), '===', false, '->getNext() returns false.');
$t->cmp_ok($album3->getNext(2), '===', false, '->getNext() returns false.');
$t->cmp_ok($album3->getNext(3), '===', false, '->getNext() returns false.');

// getCoverImage()
$album1cover = $album1->getCoverImage();
$t->isa_ok($album1cover, 'File', "->getCoverImage() returns File's instance.");
$t->cmp_ok($album2->getCoverImage(), '===', null, '->getCoverImage() returns null.');

// getAlbumImages()
$album1images = $album1->getAlbumImages();
$t->isa_ok($album1images, 'array', "->getAlbumImages() returns array.");
$t->is(count($album1images), 1, '->getAlbumImages() returns array that has 1 item.');

$album3images = $album3->getAlbumImages();
$t->isa_ok($album3images, 'array', "->getAlbumImages() returns array.");
$t->is(count($album3images), 0, '->getAlbumImages() returns array that has no item.');

// isAuthor()
$t->is($album1->isAuthor(1), true, '->getAuthor() returns true.');
$t->is($album1->isAuthor(2), false, '->getAuthor() returns false.');

// isViewable()
$t->is($album1->isViewable(1), true, '->isViewable() returns true.');
$t->is($album1->isViewable(2), true, '->isViewable() returns true.');
$t->is($album1->isViewable(3), true, '->isViewable() returns true.');

$t->is($album2->isViewable(1), true, '->isViewable() returns true.');
$t->is($album2->isViewable(2), true, '->isViewable() returns true.');
$t->is($album2->isViewable(3), false, '->isViewable() returns false.');

$t->is($album3->isViewable(1), true, '->isViewable() returns true.');
$t->is($album3->isViewable(2), false, '->isViewable() returns false.');
$t->is($album3->isViewable(3), false, '->isViewable() returns false.');
