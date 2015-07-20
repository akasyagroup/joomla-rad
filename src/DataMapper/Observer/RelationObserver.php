<?php
/**
 * Part of Windwalker project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Windwalker\DataMapper\Observer;

use JObservableInterface;
use JObserverInterface;
use Windwalker\Data\DataSet;
use Windwalker\Relation\Handler\AbstractRelationHandler;
use Windwalker\Table\Table;
use Windwalker\Utilities\ArrayHelper;

/**
 * The RelationObserver class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class RelationObserver extends AbstractDataMapperObserver
{
	/**
	 * Property deleteTempDataset.
	 *
	 * @var  DataSet
	 */
	protected $deleteTempDataset;

	/**
	 * Property parentTable.
	 *
	 * @var  Table
	 */
	protected $parentTable;

	/**
	 * Creates the associated observer instance and attaches it to the $observableObject
	 *
	 * @param   JObservableInterface $observableObject The observable subject object
	 * @param   array                $params           Params for this observer
	 *
	 * @return  JObserverInterface
	 *
	 * @since   3.1.2
	 */
	public static function createObserver(JObservableInterface $observableObject, $params = array())
	{
		$observer = new static($observableObject, $params);

		return $observer;
	}

	/**
	 * onAfterConstruction
	 *
	 * @return  void
	 */
	public function onAfterConstruction()
	{
		$relation = $this->mapper->getRelation();
		$this->parentTable = $relation->getParent();

		$this->parentTable->_relation = $relation;
	}

	/**
	 * onAfterFind
	 *
	 * @param DataSet $dataset
	 *
	 * @return  void
	 */
	public function onAfterFind(&$dataset)
	{
		$parentTable = $this->parentTable;

		foreach ($dataset as $key => $data)
		{
			$parentTable->reset();
			$parentTable->bind($data);

			$parentTable->_relation->load();

			/** @var AbstractRelationHandler $relation */
			foreach ($parentTable->_relation->getRelations() as $relation)
			{
				$field = $relation->getField();
				ArrayHelper::setValue($data, $field, $parentTable->$field);
			}

			$dataset[$key] = $data;
		}
	}

	/**
	 * onAfterCreate
	 *
	 * @param $dataset
	 *
	 * @return  void
	 */
	public function onAfterCreate(&$dataset)
	{
		$parentTable = $this->parentTable;

		foreach ($dataset as $data)
		{
			$parentTable->reset();
			$parentTable->bind($data);

			/** @var AbstractRelationHandler $relation */
			foreach ($parentTable->_relation->getRelations() as $relation)
			{
				$field = $relation->getField();

				$parentTable->$field = ArrayHelper::getValue($data, $field);
			}

			$parentTable->_relation->store();
		}
	}

	/**
	 * onAfterUpdate
	 *
	 * @param DataSet $dataset
	 *
	 * @return  void
	 */
	public function onAfterUpdate(&$dataset)
	{
		$this->onAfterCreate($dataset);
	}

	/**
	 * onBeforeDelete
	 *
	 * @param array $conditions
	 *
	 * @return  void
	 */
	public function onBeforeDelete(&$conditions)
	{
		$this->deleteTempDataset = $this->mapper->find($conditions);
	}

	/**
	 * onAfterDelete
	 *
	 * @param boolean $result
	 *
	 * @return  void
	 */
	public function onAfterDelete(&$result)
	{
		if (!$result)
		{
			return;
		}

		$parentTable = $this->parentTable;

		$dataset = $this->deleteTempDataset;

		if ($dataset instanceof \Traversable)
		{
			$dataset = iterator_to_array($dataset);
		}

		foreach ((array) $dataset as $data)
		{
			$parentTable->reset();
			$parentTable->bind($data);

			/** @var AbstractRelationHandler $relation */
			foreach ($parentTable->_relation->getRelations() as $relation)
			{
				$field = $relation->getField();

				$parentTable->$field = ArrayHelper::getValue($data, $field);
			}

			$parentTable->_relation->delete();
		}

		$this->deleteTempDataset = null;
	}
}
