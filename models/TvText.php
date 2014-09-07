<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tv_text".
 *
 * @property integer $id
 * @property string $name
 * @property string $model
 * @property integer $model_id
 * @property string $content
 */
class TvText extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tv_text';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id', 'content'], 'required'],
            [['model_id'], 'integer'],
            [['content'], 'string'],
            [['name'], 'string', 'max' => 20],
            [['model'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'model' => 'Model',
            'model_id' => 'Model ID',
            'content' => 'Content',
        ];
    }
}
