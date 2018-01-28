<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Object;

use Joomla\CMS\Object\CMSObject;

/**
 * The basic Object class with isNull() method.
 *
 * @since 2.0
 */
class Object extends CMSObject implements NullObjectInterface
{
	/**
	 * Is this object not contain any values.
	 *
	 * @return boolean
	 */
	public function isNull()
	{
		return (boolean) $this->getProperties();
	}
}
