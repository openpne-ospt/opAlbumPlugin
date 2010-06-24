<?php 
/**
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * base actions class for the opAlbumPlugin.
 *
 * @package    OpenPNE
 * @subpackage AlbumComment 
 * @author     Nguyen Ngoc Tu <tunn@tejimaya.com>
 */
 
class opAlbumPluginAlbumCommentActions extends opAlbumPluginActions
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
  
  public function executeDeleteConfirm(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAlbumCommentDeletable());

    $this->form = new BaseForm();
  }
  
  public function executeDelete(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAlbumCommentDeletable());
    $request->checkCSRFProtection();

    $this->albumComment->delete();

    $this->getUser()->setFlash('notice', 'The comment was deleted successfully.');

    $this->redirectToAlbumShow();
  }
  
  protected function redirectToAlbumShow()
  {
    $this->redirect('@album_show?id='.$this->album->id);
  }
  
  protected function isAlbumCommentDeletable()
  {
    return $this->albumComment->isDeletable($this->getUser()->getMemberId());
  }
}