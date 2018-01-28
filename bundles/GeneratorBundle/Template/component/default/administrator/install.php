<?php
/**
 * Part of Component {{extension.name.cap}} files.
 *
 * @copyright   Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\Adapter\ComponentAdapter;
use Joomla\CMS\Installer\Installer;

defined('_JEXEC') or die;

/**
 * Script file of HelloWorld component
 *
 * @package     Joomla.Administrator
 * @subpackage  {{extension.element.lower}}
 */
class Com_{{extension.name.cap}}InstallerScript
{
	/**
	 * Method to install the component.
	 *
	 * @param ComponentAdapter $parent
	 *
	 * @return  void
	 */
	public function install(ComponentAdapter $parent)
	{
	}

	/**
	 * Method to uninstall the component.
	 *
	 * @param ComponentAdapter $parent
	 *
	 * @return  void
	 */
	public function uninstall(ComponentAdapter $parent)
	{
	}

	/**
	 * Method to update the component
	 *
	 * @param ComponentAdapter $parent
	 *
	 * @return  void
	 */
	public function update(ComponentAdapter $parent)
	{
	}

	/**
	 * ethod to run before an install/update/uninstall method
	 *
	 * @param string           $type
	 * @param ComponentAdapter $parent
	 *
	 * @return  void
	 */
	public function preflight($type, ComponentAdapter $parent)
	{
	}

	/**
	 * Method to run after an install/update/uninstall method
	 *
	 * @param string           $type
	 * @param ComponentAdapter $parent
	 *
	 * @return  void
	 */
	public function postflight($type, ComponentAdapter $parent)
	{
		$db = Factory::getDbo();

		// Get install manifest
		// ========================================================================
		$p_installer = $parent->getParent();
		$installer   = new Installer;
		$manifest    = $p_installer->manifest;
		$path        = $p_installer->getPath('source');
		$result      = array();

		$css = <<<HTML
<style type="text/css">
#ak-install-img
{
}

#ak-install-msg
{
}
</style>
HTML;

		echo $css;

		$installScript = dirname($path) . '/windwalker/src/System/installscript.php';

		if (!is_file($installScript))
		{
			$installScript = JPATH_LIBRARIES . '/windwalker/src/System/installscript.php';
		}

		include $installScript;
	}
}
