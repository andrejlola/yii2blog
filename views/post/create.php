<?php
/* @var $this yii\web\View */
/* @var $model app\models\Post */
$this->params['breadcrumbs'][] = 'Create Post';
?>
    <h1>Create Post</h1>

<?php echo $this->context->renderPartial('_form', ['model'=>$model]); ?>