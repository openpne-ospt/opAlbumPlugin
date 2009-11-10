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
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class albumImageActions extends opAlbumPluginActions
{
  public function executeAdd(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAlbumAuthor());

    $this->form = new AlbumPhotoForm(array(), array('album' => $this->album));
  }

  public function executeInsert(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAlbumAuthor());

    $this->form = new AlbumPhotoForm(array(), array('album' => $this->album));
    $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
    if ($this->form->isValid())
    {
      $this->form->save();
      $this->getUser()->setFlash('notice', 'You have just added photo(s) to your album successfully.');
      $this->redirect('@album_show?id='.$this->album->id);
    }

    $this->setTemplate('add');
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAlbumViewable());
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAlbumAuthor());

    $this->form = new AlbumImageForm($this->albumImage);
    $this->deleteForm = new sfForm();
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAlbumAuthor());

    $this->form = new AlbumImageForm($this->albumImage);
    $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
    if ($this->form->isValid())
    {
      $this->form->save();
      $this->getUser()->setFlash('notice', 'You have just updated the photo successfully.');
      $this->redirect('@album_image_show?id='.$this->albumImage->id);
    }

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAlbumAuthor());

    $request->checkCSRFProtection();

    $this->albumImage->delete();

    $this->redirect('@album_show?id='.$this->album->id);
  }
}
