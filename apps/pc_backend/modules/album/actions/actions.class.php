<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Album actions.
 *
 * @package    OpenPNE
 * @subpackage album
 * @author     Hiroki Mogi <mogi@tejimaya.net>
 */
class albumActions extends sfActions
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
    $this->pager = Doctrine::getTable('Album')->getAlbumPager($request['page'], 20, AlbumTable::PUBLIC_FLAG_PRIVATE);
    $this->pager->init();
  }

  public function executeSearch(sfWebRequest $request)
  {
    if (isset($request['id']))
    {
      $this->album = Doctrine::getTable('Album')->find($request['id']);
      $this->setTemplate('searchId');

      return sfView::SUCCESS;
    }

    $this->keyword = $request['keyword'];

    $keywords = opAlbumPluginToolkit::parseKeyword($this->keyword);
    $this->forwardUnless($keywords, 'album', 'list');

    $this->pager = Doctrine::getTable('Album')->getAlbumSearchPager($keywords, $request['page'], 20, AlbumTable::PUBLIC_FLAG_PRIVATE);
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

    $this->album->delete();

    $this->getUser()->setFlash('notice', 'The album was deleted successfully.');

    $this->redirect('album/list');
  }
}
