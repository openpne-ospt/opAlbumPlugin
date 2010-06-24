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
class albumCommentComponents extends opAlbumPluginAlbumCommentComponents
{
  public function executeList(sfWebRequest $request)
  {
    $this->size = sfConfig::get('app_max_comments_on_album', 10);

    parent::executeList($request);
  }
}
