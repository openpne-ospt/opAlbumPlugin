<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * diaryComment components.
 *
 * @package    OpenPNE
 * @subpackage album
 * @author     Nguyen Ngoc Tu <tunn@tejimaya.com>
 */
class opAlbumPluginAlbumImageCommentComponents extends sfComponents
{
  public function executeList(sfWebRequest $request)
  {
    $this->pager = $this->getPager($request);
    $this->pager->init();
  }

  protected function getPager(sfWebRequest $request)
  {
    $q = Doctrine::getTable('AlbumImageComment')->createQuery()
      ->where('album_image_id = ?', $this->albumImage->id)
      ->orderBy('created_at DESC');

    $pager = new sfDoctrinePager('AlbumImageComment');
    $pager->setQuery($q);
    $pager->setPage($request['commentPage']);
    $pager->setMaxPerPage($this->size);

    return $pager;
  }
}
