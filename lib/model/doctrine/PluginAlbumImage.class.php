<?php

/**
 * PluginAlbumImage
 *
 * @package    opAlbumPlugin
 * @subpackage model
 * @author     Hiroki Mogi <mogi@tejimaya.net>
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class PluginAlbumImage extends BaseAlbumImage
{
  protected
    $previous = array(),
    $next = array();

  public function save(Doctrine_Connection $conn = null)
  {
    parent::save($conn);

    $this->setFileNamePrefix();

    return parent::save($conn);
  }

  public function preSave($event)
  {
    // album_image.member_id is must be same member
    $this->setMember($this->getAlbum()->getMember());

    if (null === $this->filesize && null !== $this->file_id)
    {
      $this->filesize = strlen($this->File->FileBin->bin);
    }
  }

  protected function setFileNamePrefix()
  {
    $prefix = 'a_'.$this->getAlbum()->id.'_';

    $file = $this->getFile();
    $file->setName($prefix.$file->getName());
  }

  public function isAuthor($memberId)
  {
    return ($this->getMemberId() == $memberId);
  }

  public function getPrevious($myMemberId = null)
  {
    if (null == $myMemberId)
    {
      $myMemberId = sfContext::getInstance()->getUser()->getMemberId();
    }


    if (null == $this->previous[$myMemberId])
    {
      $this->previous[$myMemberId] = $this->getTable()->getPreviousAlbumImage($this, $myMemberId);
    }

    return $this->previous[$myMemberId];
  }

  public function getNext($myMemberId = null)
  {
    if (null == $myMemberId)
    {
      $myMemberId = sfContext::getInstance()->getUser()->getMemberId();
    }

    if (null == $this->next[$myMemberId])
    {
      $this->next[$myMemberId] = $this->getTable()->getNextAlbumImage($this, $myMemberId);
    }

    return $this->next[$myMemberId];
  }
}
