<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_login
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
?>
<?php if ($type == 'logout') : ?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form">
<?php if ($params->get('greeting')) : ?>
	<div class="login-greeting">
	<?php if($params->get('name') == 0) : {
		echo JText::sprintf('MOD_LOGIN_HINAME', $user->get('name'));
	} else : {
		echo JText::sprintf('MOD_LOGIN_HINAME', $user->get('username'));
	} endif; ?>
	</div>
<?php endif; ?>
	<div class="logout-button">
		<input type="submit" name="Submit" class="button" value="<?php echo JText::_('JLOGOUT'); ?>" />
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.logout" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<?php else : ?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" >
	<?php if ($params->get('pretext')): ?>
		<div class="pretext">
		<p><?php echo $params->get('pretext'); ?></p>
		</div>
	<?php endif; ?>

	
		<input 
			id="modlgn-username" 
			type="text" 
			name="username" 
			class="inputbox" 
			value="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>"
			onfocus="if (this.value=='<?php echo JText::_("MOD_LOGIN_VALUE_USERNAME") ?>') this.value='' "
			onblur="if (this.value=='') this.value='<?php echo JText::_("MOD_LOGIN_VALUE_USERNAME") ?>'"
			size="18" 
		/>
	
		<input id="modlgn-passwd" 
			type="password" 
			name="password" 
			class="inputbox" 
			value="<?php echo JText::_("JGLOBAL_PASSWORD") ?>"
			onfocus="if (this.value=='<?php echo JText::_("JGLOBAL_PASSWORD") ?>') this.value=''"
			size="18"  
		/>
	
	<div class="submit">
		<input type="submit" name="Submit" class="button" value="<?php echo JText::_('JLOGIN') ?>" />
	</div>
	<input type="hidden" name="option" value="com_users" />
	<input type="hidden" name="task" value="user.login" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<?php echo JHtml::_('form.token'); ?>
	
	<br />
	<a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>"><?php echo JText::_('MOD_LOGIN_REGISTER'); ?></a>
	 | 
	<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a>
		
	<?php if ($params->get('posttext')): ?>
		<div class="posttext">
		<p><?php echo $params->get('posttext'); ?></p>
		</div>
	<?php endif; ?>
</form>
<?php endif; ?>
