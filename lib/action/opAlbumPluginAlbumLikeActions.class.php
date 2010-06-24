<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * albumLike actions.
 *
 * @package    OpenPNE
 * @subpackage albumLike
 * @author     Nguyen Ngoc Tu (tunn@tejimaya.com)
 */
class opAlbumPluginAlbumLikeActions extends opAlbumPluginActions
{
  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAlbumViewable());
    
    if (!$this->album->isLiked($this->getUser()->getMemberId()))
    {
      $like = new AlbumLike();
      $like->Album = $this->album;
      $like->member_id = $this->getUser()->getMemberId();
    
      $like->save();
    }
    
    $this->redirectToAlbumShow();
  }
  
  public function executeDelete(sfWebRequest $request)
  {
    $this->forward404Unless($this->albumLike && $this->isAlbumLikeDeletable());
    
    $this->albumLike->delete();
    
    $this->redirectToAlbumShow();
  }
  
  protected function redirectToAlbumShow()
  {
    $this->redirect('@album_show?id='.$this->album->id);
  }
  
  protected function isAlbumLikeDeletable()
  {
    return $this->albumLike->isDeletable($this->getUser()->getMemberId());
  }
}
