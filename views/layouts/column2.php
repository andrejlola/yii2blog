<?php
use app\widgets\TagCloud;
use app\widgets\UserMenu;
use app\widgets\RecentComments;
/* @var $content string */
?>
<?php $this->beginContent('@app/views/layouts/main.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <?php echo $content; ?>
            </div>
            <div class="col-md-3">
                <?php if(!Yii::$app->user->isGuest) echo UserMenu::widget(); ?>
                <?php
                echo TagCloud::widget([
                    'maxTags' => \Yii::$app->params['tagCloudCount'],
                ]);
                ?>
                <?php
                echo RecentComments::widget([
                    'maxComments' => \Yii::$app->params['recentCommentsCount'],
                ]);
                ?>
            </div>
        </div>
    </div>
<?php $this->endContent(); ?>