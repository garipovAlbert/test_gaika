<?php

namespace app\components;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Поведение для моделей ActiveRecord, которое добавляет особые поля.
 * </code>
 * Таблица с данными:
 * <code>
 * CREATE TABLE `tv_text` (
 * 	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 * 	`name` varchar(20) NOT NULL DEFAULT '',
 * 	`model` varchar(255) NOT NULL DEFAULT '',
 * 	`model_id` int(11) unsigned NOT NULL,
 * 	`content` text NOT NULL,
 * 	PRIMARY KEY (`id`)
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 * </code>
 */
class TvTextBehavior extends Behavior
{
	
	private $_data;

	/**
	 * @var string
	 */
	public $dataTable = '{{%tv_text}}';

	/**
	 * Список имен особых полей.
	 * @var array
	 */
	public $extraFields = [];

	private function _loadData()
	{
		$data = [];

		$rows = Yii::$app->db
		->createCommand("SELECT * FROM {$this->dataTable} WHERE model=:model AND model_id=:model_id", [
			':model' => get_class($this->owner),
			':model_id' => $this->owner->id,
		])
		->queryAll();

		foreach ($rows as $row) {
			$data[$row['name']] = $row['content'];
		}

		return $data;
	}

	public function events()
	{
		return [
			ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
			ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
			ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
		];
	}

	public function afterSave($event)
	{
		// $this->_data имеет значение только в случае обращения к особому полю,
		// если обращений не было, то не производим никаких действий.
		if ($this->_data !== null) {
			$data = $this->_loadData();
			foreach ($this->_data as $name => $value) {
				if (array_key_exists($name, $data)) {
					$command = Yii::$app->db->createCommand()->update($this->dataTable, [
						'content' => $value,
					], 'model=:model AND model_id=:model_id AND name=:name', [
						':model' => get_class($this->owner),
						':model_id' => $this->owner->id,
						':name' => $name,
					]);
				} else {
					$command = Yii::$app->db->createCommand()->insert($this->dataTable, [
						'model' => get_class($this->owner),
						'model_id' => $this->owner->id,
						'name' => $name,
						'content' => $value,
					]);
				}
				$command->execute();
			}
		}
	}

	public function afterDelete($event)
	{
		Yii::$app->db->createCommand()
		->delete($this->dataTable, 'model=:model AND model_id=:model_id', [
			'model' => get_class($this->owner),
			'model_id' => $this->owner->id,
		])
		->execute();
	}

	public function __get($name)
	{
		if (in_array($name, $this->extraFields)) {
			if ($this->_data === null) {
				$this->_data = $this->_loadData();
			}
			return array_key_exists($name, $this->_data) ? $this->_data[$name] : null;
		} else {
			return parent::__get($name);
		}
	}

	public function __set($name, $value)
	{
		if (in_array($name, $this->extraFields)) {
			if ($this->_data === null) {
				$this->_data = $this->_loadData();
			}
			return $this->_data[$name] = $value;
		} else {
			return parent::__set($name, $value);
		}
	}

	public function canSetProperty($name, $checkVars = true)
	{
		if (in_array($name, $this->extraFields)) {
			return true;
		} else {
			parent::canGetProperty($name, $checkVars);
		}
	}

	public function canGetProperty($name, $checkVars = true)
	{
		if (in_array($name, $this->extraFields)) {
			return true;
		} else {
			parent::canGetProperty($name, $checkVars);
		}
	}

}