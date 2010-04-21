<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * PluginAlbumComment form.
 *
 * @package    opAlbumPlugin
 * @subpackage form
 * @author     Nguyen Ngoc Tu <tunn@tejimaya.com>
 */
abstract class PluginAlbumCommentForm extends BaseAlbumCommentForm
{
  public function setup()
  {
    parent::setup();
     
    $this->useFields(array('body'));
  }
}
