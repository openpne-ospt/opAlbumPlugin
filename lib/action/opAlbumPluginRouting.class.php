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
 * @package    OpenPNE
 * @author     Mogi Hiroki <mogi@tejimaya.com>
 */
class opAlbumPluginRouting
{
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $routing = $event->getSubject();

    $routes = array(
      'album_index' => new sfRoute(
        '/album',
        array('module' => 'album', 'action' => 'index')
      ),

      'album_show' => new sfDoctrineRoute(
        '/album/:id',
        array('module' => 'album', 'action' => 'show'),
        array('id' => '\d+'),
        array('model' => 'Album', 'type' => 'object')
      ),
    );
    
    $routes = array_reverse($routes);
    foreach ($routes as $name => $route)
    {
      $routing->prependRoute($name, $route);
    }
  }
}
