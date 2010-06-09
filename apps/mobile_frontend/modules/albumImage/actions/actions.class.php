<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * albumImage actions.
 *
 * @package    OpenPNE
 * @subpackage albumImage
 * @author     Nguyen Ngoc Tu <tunn@tejimaya.com>
 */
class albumImageActions extends opAlbumPluginActions
{
  public function executeShow(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAlbumViewable());
  }
}
