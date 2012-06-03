<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_lastfm
 */

// No Acceso Directo
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';

$params->def('greeting', 1);

$type	= modLoginHelper::getType();
$return	= modLoginHelper::getReturnURL($params, $type);
$user	= JFactory::getUser();

require JModuleHelper::getLayoutPath('mod_lastfm', $params->get('layout', 'default'));
