<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * album components.
 *
 * @package    opAlbum
 * @subpackage action
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class AlbumComponents extends sfComponents
{
  public function executeSidemenu(sfWebRequest $request)
  {
     $this->pager = Doctrine::getTable('Album')->getMemberAlbumPager($this->member->getId(), $request->getParameter('page'), 10, $this->getUser()->getMemberId());

     // Recent Album List
    $this->recentAlbumList = Doctrine::getTable('Album')->getMemberAlbumList($this->member->getId(), 5, $this->getUser()->getMemberId());
  }

  public function executeAlbumList()
  {
    $max = ($this->gadget) ? $this->gadget->getConfig('max') : 5;
    $this->albumList = Doctrine::getTable('Album')->getAlbumList($max);
  }

  public function executeMyAlbumList()
  {
    $max = ($this->gadget) ? $this->gadget->getConfig('max') : 5;
    $this->albumList = Doctrine::getTable('Album')->getMemberAlbumList($this->getUser()->getMemberId(), $max, $this->getUser()->getMemberId());
  }

  public function executeFriendAlbumList()
  {
    $max = ($this->gadget) ? $this->gadget->getConfig('max') : 5;
    $this->albumList = Doctrine::getTable('Album')->getFriendAlbumList($this->getUser()->getMemberId(), $max);
  }

  public function executeMemberAlbumList(sfWebRequest $request)
  {
    $this->memberId = $request->getParameter('id', $this->getUser()->getMemberId());
    $this->albumList = Doctrine::getTable('Album')->getMemberAlbumList($this->memberId, 5, $this->getUser()->getMemberId());
  }
}
