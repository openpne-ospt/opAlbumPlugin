<?php

/**
 * PluginAlbum form.
 *
 * @package    form
 * @subpackage Album
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
abstract class PluginAlbumForm extends BaseAlbumForm
{
  public function setup()
  {
    parent::setup();

    unset($this['id']);
    unset($this['member_id']);
    unset($this['created_at']);
    unset($this['updated_at']);
    unset($this['has_images']);

    $this->widgetSchema['title'] = new sfWidgetFormInput();
    $this->widgetSchema['public_flag'] = new sfWidgetFormChoice(array(
      'choices'  => Doctrine::getTable('Diary')->getPublicFlags(),
      'expanded' => true,
    ));
    $this->validatorSchema['public_flag'] = new sfValidatorChoice(array(
      'choices' => array_keys(Doctrine::getTable('Diary')->getPublicFlags()),
    ));

/*     
    if (sfConfig::get('app_Album_is_upload_images', true))
    {
      $images = array();
      if (!$this->isNew())
      {
        $images = $this->getObject()->getAlbumImages();
      }

      if (isset($images))
      {
        $image = $images;
      }
      else
      {
        $image = new AlbumImage();
        $image->setAlbum($this->getObject());
      }

      $imageForm = new AlbumImageForm($image);
      $imageForm->getWidgetSchema()->setFormFormatterName('list');
      $this->embedForm('photo', $imageForm, '<ul id="album_photo">%content%</ul>');
    }
 */
  }

/*  
  public function updateObject($values = null)
  {
    $object = parent::updateObject($values);

    foreach ($this->embeddedForms as $key => $form)
    {
      if (!($form->getObject() && $form->getObject()->getFile()))
      {
        unset($this->embeddedForms[$key]);
      }
    }
    return $object;
  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->getObject()->updateHasImages();
  }
*/ 
}
