<?php
namespace app\controllers;
use \Yii;
use \yii\filters\AccessControl;
use \yii\web\HttpException;
use \yii\web\Controller;
use \yii\data\Pagination;

use app\models\Comment;

class CommentController extends Controller
{
    public $layout='column2';

    /**
     * @var CActiveRecord the currently loaded data model instance.
     */
    private $_model;

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true, // allow authenticated users to access all actions
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false
                    ],
                ],
            ],
        ];
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        echo $this->render(
            'update',
            ['model' => $model,]
        );
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     */
    public function actionDelete($id)
    {
        if (Yii::$app->request->isPost) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_POST['ajax'])) {
                return $this->redirect(['index']);
            }
        } else {
            throw new HttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $query = Comment::find()
            ->where(['status' => Comment::STATUS_PENDING])
            ->orderBy('status, create_time DESC');

        $countQuery = clone $query;
        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $countQuery->count()
        ]);

        $models = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->with('post')
            ->all();

        echo $this->render(
            'index',
            [
                'models' => $models,
                'pagination' => $pagination
            ]
        );
    }

    public function actionApprove($id)
    {
        $comment = $this->loadModel($id);
        $comment->approve();
        return $this->redirect(['index']);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     */
    public function loadModel($id = '')
    {
        if ($this->_model === null) {
            if (!empty($id)) {
                $this->_model = Comment::findOne($id);
            }
            if ($this->_model === null) {
                throw new HttpException(404, 'The requested page does not exist.');
            }
        }
        return $this->_model;
    }
}
