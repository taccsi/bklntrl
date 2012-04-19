<?php
/**
 * @version SVN: $Id$
 * @package    wh
 * @subpackage Views
 * @author     EasyJoomla {@link http://www.easy-joomla.org Easy-Joomla.org}
 * @author     Fuli Szabolcs {@link }
 * @author     Created on 04-Oct-2010
 */

//-- No direct access
defined('_JEXEC') or die('=;)');

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the wh Component
 *
 * @package    wh
 * @subpackage Views
 */
class whViewwh extends JView
{
    /**
     * wh view display method
     * @return void
     **/
    function display($tpl = null)
    {
        $random = $this->get('random');
        $this->assignRef('random', $random);

        $this->setLayout('raw');

        parent::display($tpl);
    }//function

}//class
