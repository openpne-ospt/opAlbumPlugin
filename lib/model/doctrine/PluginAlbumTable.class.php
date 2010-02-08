<?php
/**
 */
class PluginAlbumTable extends Doctrine_Table
{
  const PUBLIC_FLAG_OPEN    = 4;
  const PUBLIC_FLAG_SNS     = 1;
  const PUBLIC_FLAG_FRIEND  = 2;
  const PUBLIC_FLAG_PRIVATE = 3;

  protected static $publicFlags = array(
    self::PUBLIC_FLAG_OPEN    => 'All Users on the Web',
    self::PUBLIC_FLAG_SNS     => 'All Members',
    self::PUBLIC_FLAG_FRIEND  => 'My Friends',
    self::PUBLIC_FLAG_PRIVATE => 'Private',
  );

  public function getPublicFlags()
  {
    if (!sfConfig::get('app_op_album_plugin_is_open', false))
    {
      unset(self::$publicFlags[self::PUBLIC_FLAG_OPEN]);
    }

    return array_map(array(sfContext::getInstance()->getI18N(), '__'), self::$publicFlags);
  }

  public function getAlbumList($limit = 5, $publicFlag = self::PUBLIC_FLAG_SNS)
  {
    $q = $this->getOrderdQuery();
    $this->addPublicFlagQuery($q, $publicFlag);
    $q->limit($limit);

    return $q->execute();
  }

  public function getAlbumPager($page = 1, $size = 20, $publicFlag = self::PUBLIC_FLAG_SNS)
  {
    $q = $this->getOrderdQuery();
    $this->addPublicFlagQuery($q, $publicFlag);

    return $this->getPager($q, $page, $size);
  }
  
  /**
   * Search keywords for albums in the title and body
   */
  public function getAlbumSearchPager($keywords, $page = 1, $size = 20, $publicFlag = self::PUBLIC_FLAG_SNS)
  {
    $q = $this->getOrderdQuery();
    $this->addPublicFlagQuery($q, $publicFlag);
    $this->addSearchKeywordQuery($q, $keywords);

    return $this->getPager($q, $page, $size);
  }


  public function getMemberAlbumList($memberId, $limit = 5, $myMemberId = null)
  {
    $q = $this->getOrderdQuery();
    $this->addMemberQuery($q, $memberId, $myMemberId);
    $q->limit($limit);

    return $q->execute();
  }

  public function getMemberAlbumPager($memberId, $page = 1, $size = 20, $myMemberId = null)
  {
    $q = $this->getOrderdQuery();
    $this->addMemberQuery($q, $memberId, $myMemberId);

    return $this->getPager($q, $page, $size);
  }

  public function getFriendAlbumList($memberId, $limit = 5)
  {
    $q = $this->getOrderdQuery();
    $this->addFriendQuery($q, $memberId);
    $q->limit($limit);

    return $q->execute();
  }

  public function getFriendAlbumPager($memberId, $page = 1, $size = 20)
  {
    $q = $this->getOrderdQuery();
    $this->addFriendQuery($q, $memberId);

    return $this->getPager($q, $page, $size);
  }

  protected function getPager(Doctrine_Query $q, $page, $size)
  {
    $pager = new sfDoctrinePager('Album', $size);
    $pager->setQuery($q);
    $pager->setPage($page);

    return $pager;
  }

  protected function getOrderdQuery()
  {
    return $this->createQuery()->orderBy('created_at DESC');
  }

  protected function addMemberQuery(Doctrine_Query $q, $memberId, $myMemberId)
  {
    $q->andWhere('member_id = ?', $memberId);
    $this->addPublicFlagQuery($q, self::getPublicFlagByMemberId($memberId, $myMemberId));
  }

  protected function addFriendQuery(Doctrine_Query $q, $memberId)
  {
    $friendIds = Doctrine::getTable('MemberRelationship')->getFriendMemberIds($memberId, 5);
    if (!$friendIds)
    {
      $q->andWhere('1 = 0');

      return;
    }

    $q->andWhereIn('member_id', $friendIds);
    $this->addPublicFlagQuery($q, self::PUBLIC_FLAG_FRIEND);
  }

  public function addPublicFlagQuery(Doctrine_Query $q, $flag)
  {
    if ($flag === self::PUBLIC_FLAG_PRIVATE)
    {
      return;
    }

    $flags = self::getViewablePublicFlags($flag);
    if (1 === count($flags))
    {
      $q->andWhere('public_flag = ?', array_shift($flags));
    }
    else
    {
      $q->andWhereIn('public_flag', $flags);
    }
  }

  public function getPublicFlagByMemberId($memberId, $myMemberId, $forceFlag = null)
  {
    if ($forceFlag)
    {
      return $forceFlag;
    }

    if ($memberId == $myMemberId)
    {
      return self::PUBLIC_FLAG_PRIVATE;
    }

    $relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($myMemberId, $memberId);
    if ($relation && $relation->isFriend())
    {
      return self::PUBLIC_FLAG_FRIEND;
    }
    else
    {
      return self::PUBLIC_FLAG_SNS;
    }
  }

  public function getViewablePublicFlags($flag)
  {
    $flags = array();
    switch ($flag)
    {
      case self::PUBLIC_FLAG_PRIVATE:
        $flags[] = self::PUBLIC_FLAG_PRIVATE;
      case self::PUBLIC_FLAG_FRIEND:
        $flags[] = self::PUBLIC_FLAG_FRIEND;
      case self::PUBLIC_FLAG_SNS:
        $flags[] = self::PUBLIC_FLAG_SNS;
      case self::PUBLIC_FLAG_OPEN:
        $flags[] = self::PUBLIC_FLAG_OPEN;
        break;
    }

    return $flags;
  }

  public function getPreviousAlbum(Album $album, $myMemberId)
  {
    $q = $this->createQuery()
      ->andWhere('member_id = ?', $album->getMemberId())
      ->andWhere('id < ?', $album->getId())
      ->orderBy('id DESC');
    $this->addPublicFlagQuery($q, $this->getPublicFlagByMemberId($album->getMemberId(), $myMemberId));

    return $q->fetchOne();
  }

  public function getNextAlbum(Album $album, $myMemberId)
  {
    $q = $this->createQuery()
      ->andWhere('member_id = ?', $album->getMemberId())
      ->andWhere('id > ?', $album->getId())
      ->orderBy('id ASC');
    $this->addPublicFlagQuery($q, $this->getPublicFlagByMemberId($album->getMemberId(), $myMemberId));

    return $q->fetchOne();
  }

  protected function addSearchKeywordQuery(Doctrine_Query $q, $keywords)
  {
    foreach ($keywords as $keyword)
    {
      $q->andWhere('title LIKE ? OR body LIKE ?', array('%'.$keyword.'%', '%'.$keyword.'%'));
    }
  }
}
