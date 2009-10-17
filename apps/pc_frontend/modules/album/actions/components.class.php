<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * album actions.
 *
 * @package    OpenPNE
 * @subpackage album
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class AlbumComponents extends sfComponents
{
 public function executeSidemenu(sfWebRequest $request)
  {
     $this->pager = Doctrine::getTable('Album')->getMemberAlbumPager($this->member->getId(), $request->getParameter('page'), 10, $this->getUser()->getMemberId());

     // Recent Album List
    $this->recentAlbumList = Doctrine::getTable('Album')->getMemberAlbumList($this->member->getId(), 5, $this->getUser()->getMemberId());
  }
}
