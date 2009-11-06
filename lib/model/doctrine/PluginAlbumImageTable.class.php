<?php
/**
 */
class PluginAlbumImageTable extends Doctrine_Table
{
  public function getAlbumImagePager(Album $album, $page = 1, $size = 10)
  {
    $q = $this->createQuery()
      ->where('album_id = ?', $album->id);

    $pager = new sfDoctrinePager('AlbumImage', $size);
    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }
}
