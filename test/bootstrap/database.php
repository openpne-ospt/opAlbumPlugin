<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

// guess current application
if (!isset($app))
{
  $traces = debug_backtrace();
  $caller = $traces[0];

  $dirPieces = explode(DIRECTORY_SEPARATOR, dirname($caller['file']));
  $app = array_pop($dirPieces);
}

$testRevision = 1;

$configuration = ProjectConfiguration::getApplicationConfiguration($app, 'test', true);
new sfDatabaseManager($configuration);

try
{
  if ($testRevision > (int)Doctrine::getTable('SnsConfig')->get('opOpenSocialPlugin_test_revision'))
  {
    throw new Exception();
  }
}
catch (Exception $e)
{
  // for OpenPNE 3.2.x >=
  $task = new sfDoctrineBuildTask($configuration->getEventDispatcher(), new sfFormatter());
  $task->setConfiguration($configuration);
  $task->run(array(), array(
    'no-confirmation' => true,
    'db'              => true,
    'and-load'        => dirname(__FILE__).'/../fixtures',
  ));

  $snsConfig = Doctrine::getTable('SnsConfig');
  $snsConfig->set('opOpenSocialPlugin_test_revision', $testRevision);
}
