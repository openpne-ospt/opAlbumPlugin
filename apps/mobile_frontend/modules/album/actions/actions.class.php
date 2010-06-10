<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * album actions.
 *
 * @package    opAlbumPlugin
 * @subpackage album
 * @author     Nguyen Ngoc Tu <tunn@vysajp.org>
 */
class albumActions extends opAlbumPluginActions
{
  public function executeListMember(sfWebRequest $request)
  {
    $this->pager = Doctrine::getTable('Album')->getMemberAlbumPager($this->member->getId(), $request->getParameter('page'), 5, $this->getUser()->getMemberId());
  }
  
  public function executeListFriend(sfWebRequest $request)
  {
    $this->pager = Doctrine::getTable('Album')->getFriendAlbumPager($this->getUser()->getMemberId(), $request->getParameter('page'), 5);
  }
  
  public function executeList(sfWebRequest $request)
  {
    $this->pager = Doctrine::getTable('Album')->getAlbumPager($request->getParameter('page'), 5);
  }
  
  public function executeShow(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAlbumViewable());

    $this->pager = Doctrine::getTable('AlbumImage')->getAlbumImagePager($this->album, $request->getParameter('page', 1), 10);
  }
  
  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAlbumAuthor());

    $this->form = new AlbumForm($this->album);
  }
  
  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAlbumAuthor());

    $this->form = new AlbumForm($this->album);
    unset($this->form['file_id']);
    $this->form->bind($request->getParameter($this->form->getName()));
    if ($this->form->isValid())
    {
      $this->form->save();

      $this->redirect('@album_show?id='.$this->album->id);
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

    $this->album->delete();

    $this->getUser()->setFlash('notice', 'The album was deleted successfully.');

    $this->redirect('album_list_mine');
  }
}
