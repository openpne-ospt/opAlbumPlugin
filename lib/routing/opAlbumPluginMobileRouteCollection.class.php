<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Album routing.
 *
 * @package    opAlbumPlugin
 * @author     Nguyen Ngoc Tu <tunn@tejimaya.com>
 */
class opAlbumPluginMobileRouteCollection extends opAlbumPluginFrontendRouteCollection
{
  protected function generateRoutes()
  {
    $this->routes = parent::generateRoutes();
    $this->routes += array(
      // album    
    );

    return $this->routes;
  }
}
