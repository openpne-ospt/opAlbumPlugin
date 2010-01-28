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
  protected $previous, $next;

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
  }

  protected function setFileNamePrefix()
  {
    $prefix = 'a_'.$this->getAlbum()->id.'_';

    $file = $this->getFile();
    $file->setName($prefix.$file->getName());
  }

  public function updateFileId()
  {
    $this->clearRelated();
    $FileId = (bool)$this->getfile_id();

    if ($FileId != $this->getFile_id())
    {
      $this->setFile_id($FileId);
      $this->save();
    }
  }

  public function getPrevious($myMemberId)
  {
    if (is_null($this->previous))
    {
      $this->previous = $this->getTable()->getPreviousAlbumImage($this, $myMemberId);
    }
    return $this->previous;
  }

  public function getNext($myMemberId = null)
  {
    if (is_null($this->next))
    {
      $this->next = $this->getTable()->getNextAlbumImage($this, $myMemberId);
    }

    return $this->next;
  }

}
