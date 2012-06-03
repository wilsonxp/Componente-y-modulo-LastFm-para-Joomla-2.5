<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');


class LastfmViewLastfm extends JView
{
	protected $data;
	protected $form;
	protected $params;
	protected $state;

	/**
	 * Method to display the view.
	 *
	 * @param	string	The template file to include
	 * @since	1.6
	 */
	public function display($tpl = null)
	{
		// Get the view data.
		$this->data		= $this->get('Data');
		$this->form		= $this->get('Form');
		$this->state	= $this->get('State');
		$this->params	= $this->state->get('params');

		//Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx'));

                
                $model =& $this->getModel();
                $datos = $model->obtenerDatos();
                $this->assignRef('datos',$datos);
		parent::display($tpl);
	}


}
