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
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class albumActions extends opAlbumPluginAlbumActions
{
 /**
  * Executes index action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }
}
