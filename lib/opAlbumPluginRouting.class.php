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
 * @author     Hiroki Mogi <mogi@tejimaya.net>
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
      'album_new' => new sfRoute(
        '/album/new',
        array('module' => 'album', 'action' => 'new')
      ),
      'album_show' => new sfDoctrineRoute(
        '/album/:id',
        array('module' => 'album', 'action' => 'show'),
        array('id' => '\d+'),
        array('model' => 'Album', 'type' => 'object')
      ), 
      'album_create' => new sfRequestRoute(
        '/album/create',
        array('module' => 'album', 'action' => 'create'),
        array('sf_method' => array('post'))
      ),
      'album_nodefaults' => new sfRoute(
        '/album/*',
        array('module' => 'default', 'action' => 'error')
      ),
    );  

    $routes = array_reverse($routes);
    foreach ($routes as $name => $route)
    {   
      $routing->prependRoute($name, $route);
    }   
  }
}
