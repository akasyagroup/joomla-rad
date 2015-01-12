<?php
/**
 * Part of Component {{extension.name.cap}} files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace {{extension.name.cap}}\Config;

use Windwalker\System\Config\AbstractConfig;
use Windwalker\Helper\PathHelper;

// No direct access
defined('_JEXEC') or die;

/**
 * {{extension.name.cap}} config.
 *
 * @since 1.0
 */
abstract class Config extends AbstractConfig
{
	/**
	 * Config file type.
	 *
	 * @var  string
	 */
	protected static $type = 'json';

	/**
	 * Get config file path.
	 *
	 * @return  string
	 */
	public static function getPath()
	{
		$type = static::$type;
		$ext  = (static::$type == 'yaml') ? 'yml' : $type;

		return PathHelper::getAdmin('com_{{extension.name.lower}}') . '/etc/config.' . $ext;
	}
}
