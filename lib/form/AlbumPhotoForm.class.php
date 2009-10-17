<?php

/**
 * PluginAlbumImage form.
 *
 * @package    form
 * @subpackage AlbumImage
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class AlbumPhotoForm extends sfForm
{
  public function setup()
  {

    $key = 'photo';
    $options = array(
        'file_src'     => '',
        'is_image'     => true,
        'with_delete'  => true,
        'label'        => $key,
      );

//    $max = (int)sfConfig::get('app_album_photo_max_image_file_num', 1);
//    for ($i = 1; $i <= $max; $i++)
//    {
//      $key = 'photo_'.$i;
      $key = 'photo';

      $options['label'] = $key;
      $this->setWidgets(array(
        $key                => new sfWidgetFormInputFileEditable($options, array('size' => 40)),
        $key.'description'  => new sfWidgetFormInput(),
      ));

      $this->setValidators(array(
        $key                => new opValidatorImageFile(array('required' => false)),
        $key.'description'  => new sfValidatorString(array('required' => false)),
      ));

//    } 
 } 

  public function updateObject($values = null)
  {
    parent::updateObject($values);

//    $max = (int)sfConfig::get('app_album_photo_max_image_file_num', 1);
//    for ($i = 1; $i <= $max; $i++)
//    {
//      $key = 'photo_'.$i;
      $key = 'photo';

      if (is_null($values))
      {
        $values = $this->values;
      }

      $values = $this->processValues($values);

      if ($values[$key] instanceof sfValidatedFile)
      {
        if (!$this->isNew())
        {
          unset($this->getObject()->File);
        }

      $file = new File();
      $file->setFromValidatedFile($values[$key]);

      $this->getObject()->setFile($file);
      }
      else
      {
        if (!$this->isNew() && !empty($values[$key.'delete']))
        {
          $this->getObject()->getFile()->delete();
        }

      $this->getObject()->setFile(null);
      }
//    }
  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->getObject()->updateFileId();
//    $this->getObject()->updateFileId();
  }
}
