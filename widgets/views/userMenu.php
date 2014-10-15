<?php
use \yii\helpers\Html;
use app\models\Comment;
$attrs = [
    'class' => 'list-group-item',
];
$pendingCommentCount = Comment::getPendingCommentCount();
$pendingCommentCount = $pendingCommentCount > 0 ? ' (' . $pendingCommentCount . ')' : '';
?>
<div class="list-group">
    <?php echo Html::a('Create New Post', ['post/create'], $attrs); ?>
    <?php echo Html::a('Manage Posts', ['post/admin'], $attrs); ?>
    <?php echo Html::a('Approve Comments'.$pendingCommentCount, ['comment/index'], $attrs); ?>
    <?php echo Html::a('Logout', ['site/logout'], $attrs); ?>
</div>