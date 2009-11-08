<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opWidgetFormInputAlbumImage
 *
 * @package    OpenPNE
 * @subpackage widget
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opWidgetFormInputAlbumImage extends sfWidgetForm
{
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('file_label', 'File');
    $this->addOption('description_label', 'Description');
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $i18n = sfContext::getInstance()->getI18N();

    $widget = new sfWidgetFormInputFile(array(), $attributes);
    $file = '<dt>'.$i18n->__($this->getOption('file_label')).'</dt>'
          .'<dd>'.$widget->render($name.'[file]', null, array('id' => $this->generateId($name))).'</dd>';

    $widget = new sfWidgetFormInput(array(), $attributes);
    $description = '<dt>'.$i18n->__($this->getOption('description_label')).'</dt>'
                 .'<dd>'.$widget->render($name.'[description]').'</dd>';

    $emptyValues = $this->getOption('empty_values');

    return '<dl>'.$file.$description.'</dl>';
  }
}
