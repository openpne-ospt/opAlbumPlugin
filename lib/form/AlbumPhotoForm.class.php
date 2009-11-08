<?php

/**
 * PluginAlbumImage form.
 *
 * @package    form
 * @subpackage AlbumImage
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class AlbumPhotoForm extends sfForm
{
  protected $albumInstance = null;

  public function setup()
  {
    $photoCount = $this->getOption('photo_count', 5);
    $this->albumInstance = $this->getOption('album');
    if (!($this->albumInstance instanceof Album))
    {
      throw new InvalidArgumentException('The "album" option is required and it must be an instance of the Album class.');
    }

    $widget = new opWidgetFormInputAlbumImage(array(), array('size' => 40));
    $validator = new sfValidatorCallback(array('callback' => array($this, 'validatePhoto')));

    for ($i = 1; $i <= $photoCount; $i++)
    {
      $this->setWidget('photo_'.$i, clone $widget);
      $this->setValidator('photo_'.$i, clone $validator);
    }

    $this->getValidatorSchema()->setPostValidator(new sfValidatorCallback(
      array('callback' => array($this, 'requiredCheck')),
      array('required' => 'You need to post at least one photo.')
    ));

    $this->widgetSchema->setNameFormat('album_photo[%s]');
  }

  public function save()
  {
    foreach ($this->getValues() as $k => $v)
    {
      if (0 !== strpos($k, 'photo_'))
      {
        continue;
      }

      if (empty($v['file']))
      {
        continue;
      }

      $file = new File();
      $file->setFromValidatedFile($v['file']);

      if (empty($v['description']))
      {
        $description = $file->getName();
      }
      else
      {
        $description = $v['description'];
      }

      $albumImage = new AlbumImage();
      $albumImage->setAlbum($this->albumInstance);
      $albumImage->setFile($file);
      $albumImage->setDescription($description);
      $albumImage->save();
    }
  }

  public function requiredCheck($validator, $value, $arguments)
  {
    foreach ($value as $k => $v)
    {
      if (0 !== strpos($k, 'photo_'))
      {
        continue;
      }

      if (!empty($v['file']))
      {
        return $value;
      }
    }

    throw new sfValidatorError($validator, 'required');
  }

  public function validatePhoto($validator, $value, $arguments)
  {
    $_validator = new sfValidatorFile();

    try
    {
      $value['file'] = $_validator->clean($value['file']);
    }
    catch (sfValidatorError $e)
    {
      if ('required' !== $e->getCode())
      {
        throw $e;
      }

      $value['description'] = '';
      $value['file'] = null;
    }

    return $value;
  }
}
