<?php
use \yii\helpers\Html;
use \yii\widgets\ActiveForm;

use app\models\Lookup;
?>

<?php $form = ActiveForm::begin(
    [
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => ['inputOptions' => ['class' => 'input-xlarge']],
    ]
); ?>

<?php echo $form->field($model,'title')->textInput(['size' => 80, 'maxlength' => 128]); ?>
<?php echo $form->field($model,'content')->textArea(['rows' => 10, 'cols' => 70]); ?>
<?php echo $form->field($model,'tags')->textInput(['size' => 50]); ?>
<?php echo $form->field($model,'status')->dropDownList(Lookup::items('PostStatus')); ?>

<div class="form-actions">
    <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Save', null, null, ['class' => 'btn btn-primary']); ?>
</div>

<?php ActiveForm::end() ?>
