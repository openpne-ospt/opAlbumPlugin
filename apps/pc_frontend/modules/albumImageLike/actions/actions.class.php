<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * albumImageLike actions.
 *
 * @package    OpenPNE
 * @subpackage albumImageLike
 * @author     Nguyen Ngoc Tu (tunn@tejimaya.com)
 */
class albumImageLikeActions extends opAlbumPluginActions
{
  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAlbumViewable());
    
    if (!$this->albumImage->isLiked($this->getUser()->getMemberId()))
    {
      $like = new AlbumImageLike();
      $like->AlbumImage = $this->albumImage;
      $like->member_id = $this->getUser()->getMemberId();
    
      $like->save();
    }
    
    $this->redirectToAlbumImageShow();
  }
  
  public function executeDelete(sfWebRequest $request)
  {
    $this->forward404Unless($this->albumImageLike && $this->isAlbumImageLikeDeletable());
    
    $this->albumImageLike->delete();
    
    $this->redirectToAlbumImageShow();
  }
  
  protected function redirectToAlbumImageShow()
  {
    $this->redirect('@album_image_show?id='.$this->albumImage->id);
  }
  
  protected function isAlbumImageLikeDeletable()
  {
    return $this->albumImageLike->isDeletable($this->getUser()->getMemberId());
  }
}
