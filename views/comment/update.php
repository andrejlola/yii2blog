<?php
$this->params['breadcrumbs'] = [
    [
        'label' => 'Comments',
        'url' => ['index']
    ],
    'Update Comment #'.$model->id,
];
?>
    <h1>Update Comment #<?php echo $model->id; ?></h1>
<?php echo $this->context->renderPartial('_form', ['model' => $model]); ?>