<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\components\TvTextBehavior;

/**
 * This is the model class for table "page".
 *
 * @property integer $id
 * @property string $alias
 * @property string $template
 * @property string $lang
 * @property string $title
 * @property string $h1
 * @property string $description
 * @property string $keywords
 * @property string $text
 * @property integer $status
 * @property string $created
 * @property string $updated
 */
class Page extends \yii\db\ActiveRecord
{

	public function behaviors()
	{
		return [
			[
				'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'created',
				'updatedAtAttribute' => 'updated',
			],
			[
				'class' => TvTextBehavior::className(),
				'extraFields' => ['phone', 'address'],
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'page';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[
				['alias', 'template', 'title', 'description', 'keywords', 'text', 'phone', 'address'],
				'required',
			],
			[['text'], 'string'],
			[
				['alias', 'title', 'h1', 'description', 'keywords', 'phone', 'address'],
				'string', 'max' => 255,
			],
			[['template'], 'string', 'max' => 11],
			[['lang'], 'string', 'max' => 3],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'alias' => 'Alias',
			'template' => 'Template',
			'lang' => 'Lang',
			'title' => 'Title',
			'h1' => 'H1',
			'description' => 'Description',
			'keywords' => 'Keywords',
			'text' => 'Text',
			'status' => 'Status',
			'created' => 'Created',
			'updated' => 'Updated',
		];
	}

}