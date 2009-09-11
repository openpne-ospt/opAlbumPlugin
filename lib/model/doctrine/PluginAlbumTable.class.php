<?php
/**
 */
class PluginAlbumTable extends Doctrine_Table
{
  const PUBLIC_FLAG_OPEN    = 4;
  const PUBLIC_FLAG_SNS     = 1;
  const PUBLIC_FLAG_FRIEND  = 2;
  const PUBLIC_FLAG_PRIVATE = 3;

  protected static $publicFlags = array(
    self::PUBLIC_FLAG_OPEN    => 'All Users on the Web',
    self::PUBLIC_FLAG_SNS     => 'All Members',
    self::PUBLIC_FLAG_FRIEND  => 'My Friends',
    self::PUBLIC_FLAG_PRIVATE => 'Private',
  );

  public function getPublicFlags()
  {
    if (!sfConfig::get('app_op_album_plugin_is_open', false))
    {
      unset(self::$publicFlags[self::PUBLIC_FLAG_OPEN]);
    }

    return array_map(array(sfContext::getInstance()->getI18N(), '__'), self::$publicFlags);
  }

}
