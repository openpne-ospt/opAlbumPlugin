<?php

/**
 * PluginAlbumImage form.
 *
 * @package    form
 * @subpackage AlbumImage
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
abstract class PluginAlbumImageForm extends BaseAlbumImageForm
{
  public function setup()
  {
    parent::setup();

    unset($this['id']
    unset($this['album_id']
    unset($this['file_id']
    unset($this['number']
    unset($this['created_at']
    unset($this['updated_at']

    $key = 'photo';

    $options = array(
        'file_src'     => '',
        'is_image'     => true,
        'with_delete'  => true,
        'label'        => false,
        'edit_mode'    => !$this->isNew(),
      );

 }
}
