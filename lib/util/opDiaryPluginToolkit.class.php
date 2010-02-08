<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAlbumPluginToolkit
 *
 * @package    OpenPNE
 * @subpackage album
 * @author     Hiroki Mogi <mogi@tejimaya.net>
 */
class opAlbumPluginToolkit
{
  static public function parseKeyword($keyword)
  {
    $keywords = array();

    // replace double-byte space with single-byte space
    $keyword = str_replace('　', ' ', $keyword);

    $parts = explode(' ', $keyword);
    foreach ($parts as $part)
    {
      $part = trim($part);
      if ('' !== $part)
      {
        $keywords[] = $part;
      }
    }

    return $keywords;
  }
}
