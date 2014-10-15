<?php
use \yii\helpers\Html;
use \yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo \yii\helpers\Markdown::process($model->content); ?>
</div>

<div id="comments">
    <?php if($model->CommentsCount > 0): ?>
        <h3>
            <?php echo $model->CommentsCount > 1 ? $model->CommentsCount . ' comments' : 'One comment'; ?>
        </h3>

        <?php echo $this->context->renderPartial(
            '_comments',
            [
                'post' => $model,
                'comments' => $model->comments,
            ]
        );?>
    <?php endif; ?>

    <h3>Leave a Comment</h3>

    <?php if(Yii::$app->session->hasFlash('commentSubmitted')): ?>
        <div class="flash-success">
            <?php echo Yii::$app->session->getFlash('commentSubmitted'); ?>
        </div>
    <?php else: ?>
        <?php echo $this->context->renderPartial(
            '/comment/_form',
            [
                'model'=>$comment,
            ]
        ); ?>
    <?php endif; ?>
</div>