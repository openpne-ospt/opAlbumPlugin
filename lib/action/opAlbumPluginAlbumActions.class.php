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
 * @package    OpenPNE
 * @subpackage Album 
 * @author     mogi hiroki <mogi@tejimaya.net>
 */
class opAlbumPluginAlbumActions extends opAlbumPluginActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('album', 'list');
  }

  public function executeList(sfWebRequest $request)
  {
    $this->pager = Doctrine::getTable('Album')->getAlbumPager($request->getParameter('page'), 20);
  }

  public function executeListMember(sfWebRequest $request)
  {
    $this->year  = (int)$request->getParameter('year');
    $this->month = (int)$request->getParameter('month');
    $this->day   = (int)$request->getParameter('day');

    if ($this->year && $this->month)
    {
      $this->forward404Unless(checkdate($this->month, ($this->day) ? $this->day : 1, $this->year), 'Invalid date format');
    }

    $this->pager = Doctrine::getTable('Album')->getMemberAlbumPager($this->member->getId(), $request->getParameter('page'), 20, $this->getUser()->getMemberId(), $this->year, $this->month, $this->day);
  }

  public function executeListFriend(sfWebRequest $request)
  {
    $this->pager = Doctrine::getTable('Album')->getFriendAlbumPager($this->getUser()->getMemberId(), $request->getParameter('page'), 20);
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAlbumViewable());

    if ($this->isAlbumAuthor())
    {
//      Doctrine::getTable('album')->unregister($this->album);
    }
    $this->form = new DiaryCommentForm();
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

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind(
      $request->getParameter($form->getName()),
      $request->getFiles($form->getName())
    );
    if ($form->isValid())
    {
//      var_dump($form->getObject()->getMember()->getId());
//exit;
      $album = $form->save();

//      $this->redirect('@homepage');
    }
  }
}
