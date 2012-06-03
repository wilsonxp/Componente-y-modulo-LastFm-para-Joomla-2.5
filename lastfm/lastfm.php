<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

$controller = JController::getInstance('lastfm');
$controller->execute(JRequest::getCmd('task', 'display'));

//hacemos los includes de las funciones javascript esenciales
   JHTML::_('behavior.mootools');
   JHTML::_('behavior.modal'); 
   $doc =& JFactory::getDocument();
   $patch=JURI::root(true).DS."components".DS."com_lastfm".DS."includes";

   //esenciales
   $doc->addScript($patch."/js/funciones_esenciales.js");
   $doc->addStyleSheet($patch."/css/estilos_esenciales.css");
   
$controller->redirect();