<?php

/**
 * PluginAlbumImageComment form.
 *
 * @package    opAlbumPlugin
 * @subpackage AlbumImageComment
 * @author     Nguyen Ngoc Tu (tunn@tejimaya.com)
 */
abstract class PluginAlbumImageCommentForm extends BaseAlbumImageCommentForm
{
  public function setup()
  {
    parent::setup();
     
    $this->useFields(array('body'));
  }
}
