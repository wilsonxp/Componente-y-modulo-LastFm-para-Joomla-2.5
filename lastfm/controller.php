<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class lastfmController extends JController
{

	public function display()
	{
		// Get the document object.
		$document	= JFactory::getDocument();
		
		// Set the default view name and format from the Request.
		$vName	 = JRequest::getCmd('view', 'default');
		$vFormat = $document->getType();
		$lName	 = JRequest::getCmd('layout', 'default');

		if ($view = $this->getView($vName, $vFormat)) {
			
			// Do any specific processing by view.
			switch ($vName) {
				
				// Handle view specific models.
				case 'clases':
					$model = $this->getModel($vName);
					break;
				case 'subclases':
					$model = $this->getModel($vName);
					break;
																
				default:
					$model = $this->getModel('lastfm');
					break;
			}

			// Push the model into the view (as default).
			$view->setModel($model, true);
			$view->setLayout($lName);

			// Push document object into the view.
			$view->assignRef('document', $document);

			$view->display();
		}
	}
}
