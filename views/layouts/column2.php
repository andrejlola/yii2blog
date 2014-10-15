<?php
use app\widgets\TagCloud;
/* @var $content string */
?>
<?php $this->beginContent('@app/views/layouts/main.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <?php echo $content; ?>
            </div>
            <div class="col-md-3">
                <?php
                echo TagCloud::widget([
                    'maxTags' => \Yii::$app->params['tagCloudCount'],
                ]);
                ?>
            </div>
        </div>
    </div>
<?php $this->endContent(); ?>