<?php
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';
jimport('joomla.application.component.controllerform');

class LastfmControllerLastfm extends JControllerForm 
{

    public function buscarEventos(){
        $model	= $this->getModel('lastfm');
	$model->BuscarEventos();
        exit;
    }
    
    public function cerrarSesion(){
        $model	= $this->getModel('lastfm');
	$model->cerrarSesion();
 	$this->setMessage(JText::sprintf('Sesion Cerrada', $model->getError()));
        $this->setRedirect(JRoute::_('index.php?option=com_lastfm&view=lastfm', false));
    }

    public function obtenerNoticiasFeeds(){
        $model	= $this->getModel('lastfm');
        $id = JRequest::getVar('id');
	$model->obtenerNoticiasFeeds($id);
        exit;        
    }
    
    public function buscarArtistas(){
        $model	= $this->getModel('lastfm');
        $user = JRequest::getVar('user');
	$model->buscarArtistas($user);
        exit;        
    }
	
}