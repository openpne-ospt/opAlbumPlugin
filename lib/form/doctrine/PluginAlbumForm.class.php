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

    $this->widgetSchema['title'] = new sfWidgetFormInput();

    $this->widgetSchema['public_flag'] = new sfWidgetFormChoice(array(
      'choices'  => Doctrine::getTable('album')->getPublicFlags(),
      'expanded' => true,
    ));
    $this->validatorSchema['public_flag'] = new sfValidatorChoice(array(
      'choices' => array_keys(Doctrine::getTable('album')->getPublicFlags()),
    ));

    $options = array(
        'file_src'     => '',
        'is_image'     => true,
        'with_delete'  => false,
        'label'        => 'CoverImage',
        'edit_mode'    => !$this->isNew(),
      );

    if (!$this->isNew() && $this->getObject()->getFileId())
    {
      sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
      $options['template'] = get_partial('album/formEditImage', array('image' => $this->getObject()));
      $options['with_delete'] = true;
      $this->setValidator('file_id_delete', new sfValidatorBoolean(array('required' => false)));
    }

    $this->setWidget('file_id', new sfWidgetFormInputFileEditable($options, array('size' => 40)));
    $this->setValidator('file_id', new opValidatorImageFile(array('required' => false)));
  }

  public function updateObject($values = null)
  {
    parent::updateObject($values);

    if (is_null($values))
    {
      $values = $this->values;
    }

    $values = $this->processValues($values);

    if ($values['file_id'] instanceof sfValidatedFile)
    {
      if (!$this->isNew())
      {
        unset($this->getObject()->File);
      }

      $file = new File();
      $file->setFromValidatedFile($values['file_id']);

      $this->getObject()->setFile($file);
    }
    else
    {
      if (!$this->isNew() && !empty($values['file_id_delete']))
      {
        $old = $this->getObject()->getCoverImage();
        $this->getObject()->setFile(null);
        $this->getObject()->save();

        $old->delete();
      }
    }

    return $this->getObject();
  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->getObject()->updateFileId();
  }
}
