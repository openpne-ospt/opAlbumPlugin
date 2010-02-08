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
 * @subpackage action
 * @author     Mogi Hiroki <mogi@tejimaya.net>
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class albumActions extends opAlbumPluginActions
{
  public function executeList(sfWebRequest $request)
  {
    $this->pager = Doctrine::getTable('Album')->getAlbumPager($request->getParameter('page'), 20);
  }

  public function executeListMember(sfWebRequest $request)
  {
    $this->pager = Doctrine::getTable('Album')->getMemberAlbumPager($this->member->getId(), $request->getParameter('page'), 10, $this->getUser()->getMemberId());
  }

  public function executeListFriend(sfWebRequest $request)
  {
    $this->pager = Doctrine::getTable('Album')->getFriendAlbumPager($this->getUser()->getMemberId(), $request->getParameter('page'), 20);
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAlbumViewable());

    $this->pager = Doctrine::getTable('AlbumImage')->getAlbumImagePager($this->album, $request->getParameter('page', 1), 20);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new AlbumForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->form = new AlbumForm();
    $this->form->getObject()->setMemberId($this->getUser()->getMemberId());
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
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
    $this->processForm($request, $this->form);
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

    $this->redirect('@album_list_member?id='.$this->getUser()->getMemberId());
  }

  public function executeAdd(sfWebRequest $request)
  {
    $this->form = new AlbumPhotoForm();
  }

  public function executeInsert(sfWebRequest $request)
  {
    $this->form = new AlbumPhotoForm();
    $this->form->bind($request->getParameter('photo'), $request->getFiles('photo'));
    if ($this->form->isValid())
    {
      $this->form->save();
    }

      $this->setTemplate('add');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind(
      $request->getParameter($form->getName()),
      $request->getFiles($form->getName())
    );
    if ($form->isValid())
    {
      $album = $form->save();

      $this->redirect('album/listMember?id='.$album->getMemberId());
    }
  }
}
