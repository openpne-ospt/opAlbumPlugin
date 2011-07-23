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
 * @author     Nguyen Ngoc Tu <tunn@tejimaya.com>
 */
class albumImageActions extends opAlbumPluginMailActions
{
  public function executeUpload(opMailRequest $request)
  {
    $member = $this->getRoute()->getMember();
    if (!$member)
    {
      return sfView::NONE;
    }

    $mailMessage = $request->getMailMessage();

    $album = Doctrine::getTable('Album')->find($request['id']);
    if (!$album || !$album->isAuthor($member->id))
    {
      return sfView::NONE;
    }

    $files = $this->getImageFiles($mailMessage, 5);

    foreach ($files as $file)
    {
      $albumImage = new AlbumImage();
      $albumImage->setAlbum($album);
      $albumImage->setMember($member);
      $albumImage->setFile($file);

      $albumImage->save();
    }

    return sfView::NONE;
  }
}
