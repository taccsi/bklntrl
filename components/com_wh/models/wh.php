<?php
/**
 * @version SVN: $Id$
 * @package    wh
 * @subpackage Models
 * @author     EasyJoomla {@link http://www.easy-joomla.org Easy-Joomla.org}
 * @author     Fuli Szabolcs {@link }
 * @author     Created on 04-Oct-2010
 */

//-- No direct access
defined('_JEXEC') or die('=;)');

jimport('joomla.application.component.model');

/**
 * wh Model
 *
 * @package    wh
 * @subpackage Models
 */
class whModelwh extends JModel
{
    /**
     * Gets the greetings.
     *
     * @return ObjectList The greetings to be displayed to the user
     */
    function getGreetings()
    {
        $db =& JFactory::getDBO();

        $query = 'SELECT greeting FROM #__wh';
        $db->setQuery($query);
        $greetings = $db->loadObjectList();

        return $greetings;
    }//function

    /**
     * gets a random greeting
     *
     * @return string a random greeting
     */
    function getRandom()
    {
        $greetings = $this->getGreetings();

        return $greetings[rand(0, count($greetings) - 1)]->greeting;
    }//function

}// class
