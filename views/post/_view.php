<?php

use yii\helpers\Html;
?>

<div class="post">
    <div class="title">
        <?php echo Html::a(Html::encode($model->title), ['view', 'id' => $model->id]); ?>
    </div>
    <div class="author">
        posted by <?php echo $model->author->username . ' on ' . date('F j, Y',$model->create_time); ?>
    </div>
    <div class="content">
        <?=\yii\helpers\Markdown::process($model->content)?>
    </div>
    <div class="nav">
        <b>Tags:</b>
        <?php
        echo implode(', ', $model->tagLinks);
        ?>
        <br/>
        <?php echo Html::a('Permalink', $model->url); ?> |
        <?php echo Html::a("Comments ({$model->commentsCount})",$model->url.'#comments'); ?> |
        Last updated on <?php echo date('F j, Y',$model->update_time); ?>
    </div>
</div>
