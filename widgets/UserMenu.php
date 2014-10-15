<?php
namespace app\widgets;
use \yii\helpers\Html;

class UserMenu extends Portlet
{
    public function init()
    {
        $this->title = Html::encode(\Yii::$app->user->identity->username);
        parent::init();
    }

    protected function renderContent()
    {
        echo $this->render('userMenu');
    }
}