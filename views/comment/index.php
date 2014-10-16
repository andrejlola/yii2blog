<?php
use \yii\widgets\LinkPager;

$this->params['breadcrumbs'] = ['Comments'];
?>

<h1>Comments</h1>

<?php
foreach($models as $model) {
    echo $this->context->renderPartial(
        '_view',
        ['data'=>$model]
    );
}
echo LinkPager::widget(['pagination'=>$pagination]);
?>