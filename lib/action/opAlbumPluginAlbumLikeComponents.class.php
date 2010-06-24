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
 * @subpackage albumLike
 * @author     Nguyen Ngoc Tu <tunn@tejimaya.com>
 */
class opAlbumPluginAlbumLikeComponents extends sfComponents
{
  public function executeList(sfWebRequest $request)
  {
    $this->members = $this->album->getMembersLikeAlbum();
  }
}
