<?php
/**
 * @version SVN: $Id$
 * @package    xxx
 * @subpackage Views
 * @author     EasyJoomla {@link http://www.easy-joomla.org Easy-Joomla.org}
 * @author      {@link }
 * @author     Created on 07-May-2010
 */

//-- No direct access
defined('_JEXEC') or die('=;)');

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the xxx Component
 *
 * @package    xxx
 * @subpackage Views
 */
class whViewRendeles extends JView
{
    /**
     * xxx view display method
     * @return void
     **/
    function display($tpl = null)
    {
        $model = $this->getModel();
		$task = jrequest::getvar("task");
		$this->assignRef('ajaxContent', $model->$task() );		
        $this->setLayout('raw');
        parent::display($tpl);
    }//function

}//class
