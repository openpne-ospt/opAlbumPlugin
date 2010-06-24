<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * albumImage actions.
 *
 * @package    OpenPNE
 * @subpackage albumImage
 * @author     Nguyen Ngoc Tu <tunn@tejimaya.com>
 */
class albumImageActions extends opAlbumPluginActions
{
  public function executeShow(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAlbumViewable());
    
    $this->form = new AlbumImageCommentForm();
    $this->commentPage = $request->getParameter('commentPage', 1);
  }
  
  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAlbumAuthor());

    $this->form = new AlbumImageForm($this->albumImage);
    $this->form->setAlbumChoices();
    $this->form->setWidget('description', new sfWidgetFormInput(array(), array('size' => 30)));
  }
  
  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAlbumAuthor());

    $this->form = new AlbumImageForm($this->albumImage);
    $this->form->setAlbumChoices();
    $this->form->bind($request->getParameter($this->form->getName()));
    
    if ($this->form->isValid())
    {
      $this->form->save();
      $this->getUser()->setFlash('notice', 'You have just updated the photo successfully.');
      $this->redirect('@album_image_show?id='.$this->albumImage->id);
    }

    $this->setTemplate('edit');
  }
  
  public function executeDeleteConfirm(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAlbumAuthor());
    
    $this->form = new sfForm();
  }
  
  public function executeDelete(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAlbumAuthor());

    $request->checkCSRFProtection();

    $this->albumImage->delete();

    $this->redirect('@album_show?id='.$this->album->id);
  }
}
