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

    unset($this['id']);
    unset($this['album_id']);
    unset($this['member_id']);
    unset($this['file_id']);
    unset($this['filesize']);
    unset($this['created_at']);
    unset($this['updated_at']);
    unset($this['description']);

    $key = 'photo';
    $options = array(
        'file_src'     => '',
        'is_image'     => true,
        'with_delete'  => true,
        'label'        => $key,
        'edit_mode'    => !$this->isNew(),
      );

    $max = (int)sfConfig::get('app_album_photo_max_image_file_num', 5);
    for ($i = 1; $i <= $max; $i++)
    {
      $key = 'photo_'.$i;

      $options['label'] = $key;
      $this->setWidget($key, new sfWidgetFormInputFileEditable($options, array('size' => 40)));
      $this->setValidator($key, new opValidatorImageFile(array('required' => false)));
 
      $this->setWidget($key.'description', new sfWidgetFormInput());
      $this->setValidator($key.'description', new sfValidatorString(array('required' => false)));
    } 
  } 

  public function updateObject($values = null)
  {
    if ($values['photo'] instanceof sfValidatedFile)
    {
      if (!$this->isNew())
      {
        unset($this->getObject()->File);
      }

      $file = new File();
      $file->setFromValidatedFile($values['photo']);

      $this->getObject()->setFile($file);
    }
    else
    {
      if (!$this->isNew() && !empty($values['photo_delete']))
      {
        $this->getObject()->getFile()->delete();
      }

      $this->getObject()->setFile(null);
    }
  }
}
