<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * albumComment actions.
 *
 * @package    OpenPNE
 * @subpackage albumComment
 * @author     Nguyen Ngoc Tu
 */
class albumCommentActions extends opAlbumPluginActions
{
  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAlbumViewable());
    
    $this->form = new AlbumCommentForm();
    $this->form->getObject()->Album = $this->album;
    $this->form->getObject()->member_id = $this->getUser()->getMemberId();
    
    $this->form->bind(
      $request->getParameter($this->form->getName())
    );
    
    if ($this->form->isValid())
    {
      $this->form->save();

      $this->redirectToAlbumShow();
    }
    
    $this->setTemplate('../../album/templates/show');
  }
  
  protected function redirectToAlbumShow()
  {
    $this->redirect('@album_show?id='.$this->album->id);
  }
}
