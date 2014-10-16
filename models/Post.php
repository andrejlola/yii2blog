<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
/**
 * This is the model class for table "{{%post}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $tags
 * @property integer $status
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $author_id
 *
 * @property Comment[] $comments
 * @property User $author
 */
class Post extends \yii\db\ActiveRecord
{
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_ARCHIVED = 3;

    private $_oldTags;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'status', 'author_id'], 'required'],
            [['content', 'tags'], 'string'],
            [['status', 'create_time', 'update_time', 'author_id'], 'integer'],
            [['title'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'tags' => Yii::t('app', 'Tags'),
            'status' => Yii::t('app', 'Status'),
            'create_time' => Yii::t('app', 'Create Time'),
            'update_time' => Yii::t('app', 'Update Time'),
            'author_id' => Yii::t('app', 'Author ID'),
        ];
    }

    /**
     * This is invoked when a record is populated with data from a find() call.
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->_oldTags = $this->tags;
    }

    /**
     * This is invoked before the record is saved.
     * @return boolean whether the record should be saved.
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->create_time=$this->update_time=time();
                $this->author_id=Yii::$app->user->identity->id;
            } else {
                $this->update_time=time();
            }
            return true;
        } else {
           return false;
        }
    }

//    /**
//     * This is invoked after the record is saved.
//     */
//    public function afterSave($insert)
//    {
//        parent::afterSave(true, $insert);
//        Tag::updateFrequency($this->_oldTags, $this->tags);
//    }

    /**
     * This is invoked after the record is deleted.
     */
    public function afterDelete()
    {
        if (parent::beforeDelete()) {
            Comment::deleteAll('post_id='.$this->id);
            Tag::updateFrequency($this->tags, '');
        } else {
            return false;
        }
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this
            ->hasMany(Comment::className(), ['post_id' => 'id'])
            ->andOnCondition(['status' => Comment::STATUS_APPROVED]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommentsCount()
    {
        return $this->getComments()->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * @return array a list of links that point to the post list filtered by every tag of this post
     */
    public function getTagLinks()
    {
        $links = [];
        foreach(Tag::string2array($this->tags) as $tag) {
            $links[] = Html::a(Html::encode($tag), ['post/index', 'tag' => $tag]);
        }
        return $links;
    }

    /**
     * @return string the URL that shows the detail of the post
     */
    public function getUrl()
    {
        $url = Yii::$app->getUrlManager()->createUrl(
            'post/view',
            [
                'id' => $this->id,
                'title' => $this->title,
            ]
        );
        return $url;
    }

    /**
     * Adds a new comment to this post.
     * This method will set status and post_id of the comment accordingly.
     * @param Comment $comment the comment to be added
     * @return boolean whether the comment is saved successfully
     */
    public function addComment($comment)
    {
        if(Yii::$app->params['commentNeedApproval']) {
            $comment->status = Comment::STATUS_PENDING;
        } else {
            $comment->status = Comment::STATUS_APPROVED;
        }
        $comment->post_id = $this->id;
        return $comment->save();
    }
}