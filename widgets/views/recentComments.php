<?php
use \yii\helpers\Html;
?>
<ul>
    <?php foreach($this->context->getRecentComments() as $comment): ?>
        <li><?php echo $comment->authorLink; ?> on
            <?php echo Html::a(Html::encode($comment->post->title), $comment->Url); ?>
        </li>
    <?php endforeach; ?>
</ul>