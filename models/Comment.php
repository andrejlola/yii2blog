<?php

namespace app\models;

use Yii;
use \yii\helpers\Html;

/**
 * This is the model class for table "{{%comment}}".
 *
 * @property integer $id
 * @property string $content
 * @property integer $status
 * @property integer $create_time
 * @property string $author
 * @property string $email
 * @property string $url
 * @property integer $post_id
 *
 * @property Post $post
 */
class Comment extends \yii\db\ActiveRecord
{
    const STATUS_PENDING = 1;
    const STATUS_APPROVED = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'status', 'author', 'email', 'post_id'], 'required'],
            [['content'], 'string'],
            [['status', 'create_time', 'post_id'], 'integer'],
            [['author', 'email', 'url'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'content' => Yii::t('app', 'Content'),
            'status' => Yii::t('app', 'Status'),
            'create_time' => Yii::t('app', 'Create Time'),
            'author' => Yii::t('app', 'Author'),
            'email' => Yii::t('app', 'Email'),
            'url' => Yii::t('app', 'Url'),
            'post_id' => Yii::t('app', 'Post ID'),
        ];
    }

    /**
     * This is invoked before the record is saved.
     * @return boolean whether the record should be saved.
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->create_time = time();
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    /**
     * @return integer the number of comments that are pending approval
     */
    public static function getPendingCommentCount()
    {
        return self::find()
            ->where(['status' => self::STATUS_PENDING])
            ->count()
        ;
    }

    /**
     * @param Post $post the post that this comment belongs to. If null, the method
     * will query for the post.
     * @return string the permalink URL for this comment
     */
    public function getUrl($post = null)
    {
        if($post === null) {
            $post = $this->post;
        }
        return $post->url.'#c'.$this->id;
    }

    /**
     * @return string the hyperlink display for the current comment's author
     */
    public function getAuthorLink()
    {
        if(!empty($this->url)) {
            return Html::a(Html::encode($this->author), $this->url);
        } else {
            return Html::a($this->author);
        }
    }

    /**
     * @param integer $limit the maximum number of comments that should be returned
     * @return array the most recently added comments
     */
    public static function findRecentComments($limit = 10)
    {
        return self::find()
            ->where('status='.self::STATUS_APPROVED)
            ->orderBy('create_time DESC')
            ->limit($limit)
            ->with('post')
            ->all()
        ;
    }

    /**
     * Approves a comment.
     */
    public function approve()
    {
        $this->status = Comment::STATUS_APPROVED;
        $this->update(['status']);
    }
}