<?php
/**
 */
class PluginAlbumImageTable extends Doctrine_Table
{
  public function getAlbumImagePager(Album $album, $page = 1, $size = 10)
  {
    $q = $this->createQuery()
      ->where('album_id = ?', $album->id)
      ->orderBy('created_at DESC');

    $pager = new sfDoctrinePager('AlbumImage', $size);
    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }

  public function getPreviousAlbumImage(AlbumImage $image, $myMemberId)
  {
    $q = $this->createQuery()
      ->andWhere('member_id = ?', $image->getMemberId())
      ->andWhere('album_id = ?', $image->getAlbumId())
      ->andWhere('id < ?', $image->getId())
      ->orderBy('id DESC');

    return $q->fetchOne();
  }

  public function getNextAlbumImage(AlbumImage $image, $myMemberId)
  {
    $q = $this->createQuery()
      ->andWhere('member_id = ?', $image->getMemberId())
      ->andWhere('album_id = ?', $image->getAlbumId())
      ->andWhere('id > ?', $image->getId())
      ->orderBy('id ASC');

    return $q->fetchOne();
  }
}
