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

    sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');

    $options = array(
      'file_src'    => '',
      'is_image'    => true,
      'with_delete' => false,
      'template'    => get_partial('default/formEditImage', array('image' => $this->getObject())),
    );
    $this->setWidget('photo', new sfWidgetFormInputFileEditable($options, array('size' => 40)));

    $this->setWidget('description', new sfWidgetFormInput(array(), array('size' => 40)));
    $this->getWidgetSchema()->moveField('description', sfWidgetFormSchema::LAST);

    $this->setValidator('photo', new opValidatorImageFile(array('required' => false)));
  }

  public function updateObject($values = null)
  {
    if (is_null($values))
    {
      $values = $this->values;
    }

    $photo = $values['photo'];
    unset($values['photo']);

    $object = parent::updateObject($values);

    if ($photo)
    {
      $file = new File();
      $file->setFromValidatedFile($photo);

      $old = $this->getObject()->getFile();
      $this->getObject()->setFile($file);
      $this->getObject()->save();

      $old->delete();
    }

    return $object;
  }
}
