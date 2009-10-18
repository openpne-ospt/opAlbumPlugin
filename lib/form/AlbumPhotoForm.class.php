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
  protected $memberId = null;

  public function setup()
  {
    $this->memberId = $this->getOption('member_id');
    if (!$this->memberId)
    {
      $this->memberId = sfContext::getInstance()->getUser()->getMemberId();
    }

    $options = array(
        'file_src'     => '',
        'is_image'     => true,
        'with_delete'  => true,
        'label'        => 'photo',
      );

    $this->setWidgets(array(
        'photo'                => new sfWidgetFormInputFileEditable($options, array('size' => 40)),
        'photo_description'  => new sfWidgetFormInput(),
      ));

    $this->setValidators(array(
        'photo'                => new opValidatorImageFile(array('required' => false)),
        'photo_description'  => new sfValidatorString(array('required' => false)),
      ));
    $this->widgetSchema->setNameFormat('photo[%s]');

  }
  public function save()
  {
    if(!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    $albumImage = new AlbumImage();
    $albumImage->setMemberId($this->form->getValue('memberId'));
    $albumImage->setPhoto($this->form->getValue('photo'));
    $albumImage->setName($this->form->getValue('photo_description'));

    $albumImage->save();

  } 
}
