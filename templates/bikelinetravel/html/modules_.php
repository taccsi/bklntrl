<?php
/**
 * @package		Joomla.Site
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;


function modChrome_box($module, &$params, &$attribs)
{ ?>
		<div class="moduletable<?php echo htmlspecialchars($params->get('moduleclass_sfx')); ?>">			
			<?php if ($module->showtitle != 0) : ?>
				<h3><?php echo $module->title; ?></h3>
			<?php endif; ?>
			<div class="m_inner">		
				<?php echo $module->content; ?>		
			</div>
		</div>
	<?php
}

?>
