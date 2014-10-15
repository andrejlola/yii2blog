<?php
namespace app\widgets;
use \yii\helpers\Html;

use app\models\Tag;
class TagCloud extends Portlet
{
    public $title = 'Tags';
    public $maxTags = 20;

    public function init()
    {
        parent::init();
    }

    protected function renderContent()
    {
        $tags = Tag::findTagWeights($this->maxTags);
        foreach($tags as $tag => $weight)
        {
            $a = Html::a(Html::encode($tag), ['post/index', 'tag' => $tag], ['class' => 'btn btn-primary btn-xs active']);
            echo $a."\n";
        }
    }
}