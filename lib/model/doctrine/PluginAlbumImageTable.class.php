<?php
/**
 */
class PluginAlbumImageTable extends Doctrine_Table
{
  public function getAlbumImagePager()
  {
    $args = func_get_args();

    if (is_object(func_get_arg(0)))
    {
      if ($args[0] == null){ $args[0] = $album;}
      if ($args[1] == false){ $args[1] = 1;}
      if ($args[2] == null){ $args[2] = 10;}

      $q = $this->createQuery()
          ->where('album_id = ?', $args[0]->id)
          ->orderBy('created_at DESC');

      return $this->getPager($q, $args[1], $args[2]);
    }
    else
    {
      if ($args[0] == false){ $args[0] = 1;}
      if ($args[1] == null){ $args[1] = 20;}
      $q = $this->getOrderdQuery();

      return $this->getPager($q, $args[0], $args[1]);
    } 
  }

  public function getAlbumImagePagerForAlbum($albumId, $page = 1, $size = 20)
  {
    $q = $this->createQuery()->where('album_id = ?', $albumId)->orderBy('id DESC');

    return $this->getPager($q, $page, $size);
  }

  public function getAlbumImageSearchPager($keywords, $page = 1, $size = 20)
  {
    $q = $this->getOrderdQuery();
    $this->addSearchKeywordQuery($q, $keywords);

    return $this->getPager($q, $page, $size);
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

  protected function getOrderdQuery()
  {
    return $this->createQuery()->orderBy('id DESC');
  }

  protected function getPager(Doctrine_Query $q, $page, $size)
  {
    $pager = new sfDoctrinePager('AlbumImage', $size);
    $pager->setQuery($q);
    $pager->setPage($page);

    return $pager;
  }

  protected function addSearchKeywordQuery(Doctrine_Query $q, $keywords)
  {
    foreach ($keywords as $keyword)
    {
      $q->andWhere('description LIKE ?', array('%'.$keyword.'%'));
    }
  }
}
