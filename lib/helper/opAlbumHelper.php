<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

function op_album_get_title_and_count($album, $space = true, $width = 36)
{
  return sprintf('%s%s',
           op_truncate($album->getTitle(), $width),
           $space ? ' ' : ''
         );
}
