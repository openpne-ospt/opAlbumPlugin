<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAlbumPluginBackendRouteCollection
 *
 * @package    opAlbumPlugin
 * @subpackage routing
 * @author     Hiroki Mogi <mogi@tejimaya.net>
 */
class opAlbumPluginBackendRouteCollection extends opAlbumPluginBaseRouteCollection
{
  protected function generateRoutes()
  {
    return array(
      'monitoring_album' => new sfRoute(
        '/monitoring/album',
        array('module' => 'album', 'action' => 'list'),
        array(),
        array('extra_parameters_as_query_string' => true)
      ),
      'monitoring_album_search' => new sfRoute(
        '/monitoring/album/search',
        array('module' => 'album', 'action' => 'search'),
        array(),
        array('extra_parameters_as_query_string' => true)
      ),

      'monitoring_album_image' => new sfRoute(
        '/monitoring/album/image',
        array('module' => 'albumImage', 'action' => 'list'),
        array(),
        array('extra_parameters_as_query_string' => true)
      ),
      'monitoring_album_image_search' => new sfRoute(
        '/monitoring/album/image/search',
        array('module' => 'albumImage', 'action' => 'search'),
        array(),
        array('extra_parameters_as_query_string' => true)
      ),

      'monitoring_album_delete_confirm' => new sfDoctrineRoute(
        '/monitoring/album/deleteConfirm/:id',
        array('module' => 'album', 'action' => 'deleteConfirm'),
        array('id' => '\d+'),
        array('model' => 'album', 'type' => 'object')
      ),
      'monitoring_album_delete' => new sfDoctrineRoute(
        '/monitoring/album/delete/:id',
        array('module' => 'album', 'action' => 'delete'),
        array('id' => '\d+', 'sf_method' => array('post')),
        array('model' => 'album', 'type' => 'object')
      ),

      'monitoring_album_image_delete_confirm' => new sfDoctrineRoute(
        '/monitoring/album/image/deleteConfirm/:id',
        array('module' => 'albumImage', 'action' => 'deleteConfirm'),
        array('id' => '\d+'),
        array('model' => 'albumImage', 'type' => 'object')
      ),
      'monitoring_album_image_delete' => new sfDoctrineRoute(
        '/monitoring/album/image/delete/:id',
        array('module' => 'albumImage', 'action' => 'delete'),
        array('id' => '\d+', 'sf_method' => array('post')),
        array('model' => 'albumImage', 'type' => 'object')
      ),
    );
  }
}
