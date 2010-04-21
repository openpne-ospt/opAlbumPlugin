<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * albumImageComment actions.
 *
 * @package    OpenPNE
 * @subpackage albumImageComment
 * @author     Nguyen Ngoc Tu (tunn@tejimaya.com)
 */
class albumImageCommentActions extends opAlbumPluginActions
{
  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAlbumViewable());
    
    $this->form = new AlbumImageCommentForm();
    $this->form->getObject()->AlbumImage = $this->albumImage;
    $this->form->getObject()->member_id = $this->getUser()->getMemberId();
    
    $this->form->bind(
      $request->getParameter($this->form->getName())
    );
    
    if ($this->form->isValid())
    {
      $this->form->save();

      $this->redirectToAlbumImageShow();
    }
    
    $this->setTemplate('../../albumImage/templates/show');
  }
  
  protected function redirectToAlbumImageShow()
  {
    $this->redirect('@album_image_show?id='.$this->albumImage->id);
  }
  
  public function executeDeleteConfirm(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAlbumImageCommentDeletable());

    $this->form = new BaseForm();
  }
  
  public function executeDelete(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAlbumImageCommentDeletable());
    $request->checkCSRFProtection();

    $this->albumImageComment->delete();

    $this->getUser()->setFlash('notice', 'The comment was deleted successfully.');

    $this->redirectToAlbumImageShow();
  }
  
  protected function isAlbumImageCommentDeletable()
  {
    return $this->albumImageComment->isDeletable($this->getUser()->getMemberId());
  }
}
