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
 * @author     Hiroki Mogi <mogi@tejimaya.net>
 */
class albumImageActions extends sfActions
{
  public function preExecute()
  {
    if (is_callable(array($this->getRoute(), 'getObject')))
    {
      $object = $this->getRoute()->getObject();
      if ($object instanceof Album)
      {
        $this->album = $object;
      }
      elseif ($object instanceof AlbumImage)
      {
        $this->albumImage = $object;
        $this->album = $this->albumImage->getAlbum();
      }
    }
  }

  public function executeList(sfWebRequest $request)
  {
    $this->pager = Doctrine::getTable('AlbumImage')->getAlbumImagePager($request['page'], 20);
    $this->pager->init();
  }

  public function executeSearch(sfWebRequest $request)
  {
    if (isset($request['album_id']))
    {
      $this->albumId = $request['album_id'];
      $this->pager = Doctrine::getTable('AlbumImage')->getAlbumImagePagerForAlbum($this->albumId, $request['page'], 20);
    }
    elseif (isset($request['keyword']))
    {
      $this->keyword = $request['keyword'];
      $keywords = opAlbumPluginToolkit::parseKeyword($this->keyword);
      $this->pager = Doctrine::getTable('AlbumImage')->getAlbumImageSearchPager($keywords, $request['page'], 20);
    }
    else
    {
      $this->forward('albumImage', 'list');
    }

    $this->pager->init();
    $this->setTemplate('list');
  }

  public function executeDeleteConfirm(sfWebRequest $request)
  {
    $this->form = new BaseForm();
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->albumImage->delete();

    $this->getUser()->setFlash('notice', 'The image was deleted successfully.');

    $this->redirect('albumImage/list');
  }
}
