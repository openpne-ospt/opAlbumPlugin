<?php

include dirname(__FILE__).'/../../../bootstrap/unit.php';
include dirname(__FILE__).'/../../../bootstrap/database.php';
sfContext::createInstance($configuration);
sfContext::getInstance()->getUser()->setMemberId(1);

$t = new lime_test(10, new lime_output_color());

$member1 = Doctrine::getTable('Member')->find(1);
$member2 = Doctrine::getTable('Member')->find(2);
$member3 = Doctrine::getTable('Member')->find(3);

$album1 = Doctrine::getTable('Album')->find(1);
$album2 = Doctrine::getTable('Album')->find(2);
$album3 = Doctrine::getTable('Album')->find(3);

$albumImage1 = Doctrine::getTable('AlbumImage')->find(1);
$albumImage2 = Doctrine::getTable('AlbumImage')->find(2);
$albumImage3 = Doctrine::getTable('AlbumImage')->find(3);

$conn = Doctrine::getTable('AlbumImage')->getConnection();

// isAuthor()
$t->cmp_ok($albumImage1->isAuthor(1), '===', true, '->isAuthor() returns true.');
$t->cmp_ok($albumImage1->isAuthor(2), '===', false, '->isAuthor() returns false.');

// getPrevious()
$t->cmp_ok($albumImage1->getPrevious(), '===', false, '->getPrevious() returns false.');

$result = $albumImage2->getPrevious();
$t->isa_ok($result, 'AlbumImage', "->getPrevious() returns AlbumImage's instance.");
$t->is($result->id, $albumImage1->id, '->getPrevious() returns previous AlbumImage corectlly.');

// getNext()
$result = $albumImage1->getNext();
$t->isa_ok($result, 'AlbumImage', "->getNext() returns AlbumImage's instance.");
$t->is($result->id, $albumImage2->id, '->getNext() returns next AlbumImage corectlly.');

$t->cmp_ok($albumImage2->getNext(), '===', false, '->getNext() returns false.');

// save()
$conn->beginTransaction();
$file_new = new File();

$data = array(
  'tmp_name' => dirname(__FILE__).'/../../../images/OpenPNE.jpg',
  'type'     => 'image/jpeg',
  'size'     => 8327,
  'name'     => 'OpenPNE.jpg'
);

$validatorFile = new opValidatorImageFile();
$validatedFile = $validatorFile->clean($data);
$file_new->setFromValidatedFile($validatedFile);

$albumImage_new = new AlbumImage();
$albumImage_new->setMember($member1);
$albumImage_new->setAlbum($album1);
$albumImage_new->setFile($file_new);
$albumImage_new->save();
$t->is($albumImage1->getFile()->getName(), 'a_1055_a_2_a_1_a_1_dummy_file', 'filename is setted by ->save()');
$t->is($albumImage_new->getFilesize(), 8327, 'filesize is setted by ->preSave().');

$conn->rollback();
