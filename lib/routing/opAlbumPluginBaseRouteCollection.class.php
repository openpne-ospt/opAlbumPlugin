<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAlbumPluginBaseRouteCollection
 *
 * @package    opAlbumPlugin
 * @subpackage routing
 * @author     Hiroki Mogi <mogi@tejimaya.net>
 */
abstract class opAlbumPluginBaseRouteCollection extends sfRouteCollection
{
  public function __construct(array $options)
  {
    parent::__construct($options);

    $this->routes = $this->generateRoutes();
    $this->routes += $this->generateNoDefaults();
  }

  abstract protected function generateRoutes();

  protected function generateNoDefaults()
  {
    return array(
      'album_nodefaults' => new sfRoute(
        '/album/*',
        array('module' => 'default', 'action' => 'error')
      ),
      'album_image_nodefaults' => new sfRoute(
        '/albumImage/*',
        array('module' => 'default', 'action' => 'error')
      ),
    );
  }
}
