<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * albumImageComment components.
 *
 * @package    OpenPNE
 * @subpackage album
 * @author     Nguyen Ngoc Tu <tunn@tejimaya.com>
 */

class albumImageCommentComponents extends opAlbumPluginAlbumImageCommentComponents
{
  public function executeList(sfWebRequest $request)
  {
    $this->size = sfConfig::get('app_max_comments_on_album_image',20);

    parent::executeList($request);
  }
}