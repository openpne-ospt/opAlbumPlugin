<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * base actions class for the opAlbumPlugin.
 *
 * @package    OpenPNE
 * @subpackage Album 
 * @author     mogi hiroki <mogi@tejimaya.net>
 */
class opAlbumPluginActions extends sfActions
{
  public function preExecute()
  {
    var_dump(is_callable(array($this->getRoute(), 'getObject')));
    if (is_callable(array($this->getRoute(), 'getObject')))
    {
      $object = $this->getRoute()->getObject();
var_dump($object);
      if ($object instanceof Album)
      {
  var_dump(__LINE__);
        $this->album = $object;
        $this->member = $this->album->getMember();
      }
      elseif ($object instanceof AlbumComment)
      {
        var_dump(__LINE__);
        $this->albumComment = $object;
        $this->album = $this->albumComment->getAlbum();
        $this->member = $this->album->getMember();
      }
      elseif ($object instanceof Member)
      {
  var_dump(__LINE__);
        $this->member = $object;
      }
    }

    if (empty($this->member))
    {
      $this->member = $this->getUser()->getMember();
    }
  }

  public function postExecute()
  {
    $this->setNavigation($this->member);

    if ($this->pager instanceof sfPager)
    {
      $this->pager->init();
    }
  }

  protected function setNavigation(Member $member)
  {
    if ($member->getId() !== $this->getUser()->getMemberId())
    {
      sfConfig::set('sf_nav_type', 'friend');
      sfConfig::set('sf_nav_id', $member->getId());
    }
  }

  protected function isAlbumAuthor()
  {
    return $this->album->isAuthor($this->getUser()->getMemberId());
  }

  protected function isAlbumViewable()
  {
    return $this->diary->isViewable($this->getUser()->getMemberId());
  }
}
