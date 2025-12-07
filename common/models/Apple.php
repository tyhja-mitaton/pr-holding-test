<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * @property integer $id
 * @property string $color
 * @property float $size
 * @property boolean $on_tree
 * @property integer $created_at
 * @property integer $fell_at
 */
class Apple extends ActiveRecord
{
    const ROT_TIME = 5;

    public function __construct($color = null, array $options = [])
    {
        $this->color = $color ?? $this->generateRandomHexColor();
        parent::__construct($options);
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => time(),
            ],
        ];
    }

    public static function tableName()
    {
        return 'apple';
    }

    public function rules()
    {
        return [
            [['color'], 'required'],
            [['color'], 'string'],
            [['size'], 'number'],
            [['fell_at'], 'integer'],
            [['on_tree'], 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'color' => '"Цвет"',
            'size' => 'Осталось',
            'fell_at' => 'Время падения',
            'on_tree' => 'На дереве',
        ];
    }

    public function eat(int $percent)
    {
        if(!$this->on_tree && !$this->isRotten()) {
            $this->size = max($this->size - $percent/100, 0);
            if($this->save()) {
                return $this->size;
            }
            throw new \Exception('Failed to save the result');
        }
        throw new \Exception('Apple is not editable');
    }

    public function isRotten(): bool
    {
        if($this->on_tree) {
            return false;
        }

        return (time() - $this->fell_at) >= (self::ROT_TIME * 3600);
    }

    public function remove()
    {
        if($this->size == 0) {
            return $this->delete();
        }
        return false;
    }

    public function fallToGround(): bool
    {
        $this->on_tree = 0;
        $this->fell_at = time();

        return $this->save();
    }

    private function generateRandomHexColor() {
        $randomDecimal = mt_rand(0, 16777215);
        $hexColor = dechex($randomDecimal);
        $hexColor = str_pad($hexColor, 6, '0', STR_PAD_LEFT);

        return '#' . $hexColor;
    }

}