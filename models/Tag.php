<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tag}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $frequency
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['frequency'], 'integer'],
            [['name'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'frequency' => Yii::t('app', 'Frequency'),
        ];
    }

    public static function string2array($tags)
    {
        return preg_split('/\s*,\s*/', trim($tags), -1, PREG_SPLIT_NO_EMPTY);
    }

    public static function array2string($tags)
    {
        return implode(', ', $tags);
    }

    /**
     * Returns tag names and their corresponding weights.
     * Only the tags with the top weights will be returned.
     * @param integer $limit the maximum number of tags that should be returned
     * @return array weights indexed by tag names.
     */
    public static function findTagWeights($limit = 20)
    {
        $models = self::find()
            ->orderBy('frequency DESC')
            ->limit($limit)
            ->all()
        ;

        $total = 0;
        foreach($models as $model) {
            $total += $model->frequency;
        }
        $tags = [];
        if($total > 0) {
            foreach($models as $model) {
                $tags[$model->name] = 8 + (int)(16 * $model->frequency / ($total + 10));
            }
            ksort($tags);
        }
        return $tags;
    }

    public static function addTags($tags)
    {
        if (count($tags) >0) {
            $inTags = preg_replace('/(\S+)/i', '\'\1\'', $tags);
            $sql = "UPDATE ".Tag::tableName()." SET frequency=frequency+1 WHERE name IN (". join(",", $inTags) .' ) ';
            Yii::$app->db->createCommand($sql)->execute();
            foreach($tags as $name) {
                $model = static::find()->where('name=:name',[':name' => $name])->one();
                if ($model === null) {
                    $tag = new Tag();
                    $tag->name = $name;
                    $tag->frequency = 1;
                    $tag->save();
                }
            }
        }
    }

    public static function removeTags($tags)
    {
        if(empty($tags)) {
            return;
        }
        $inTags = preg_replace('/(\S+)/i', '\'\1\'', $tags);
        $sql = "UPDATE {{%tag}} SET frequency=frequency-1 WHERE name IN (". join(",", $inTags) .' ) ';
        Yii::$app->db->createCommand($sql)->execute();
        $sql = "DELETE FROM {{%tag}} WHERE frequency<=0";
        Yii::$app->db->createCommand($sql)->execute();
    }

    public static function updateFrequency($oldTags, $newTags)
    {
        $oldTags=self::string2array($oldTags);
        $newTags=self::string2array($newTags);
        self::addTags(array_values(array_diff($newTags, $oldTags)));
        self::removeTags(array_values(array_diff($oldTags, $newTags)));
    }
}