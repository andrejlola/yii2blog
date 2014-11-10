<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use \yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\Post;
use app\models\Comment;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
    public $layout = 'column2';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'view',
                        ],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $whereCondition = [
            'status' => Post::STATUS_PUBLISHED
        ];
        $query = Post::find()->where($whereCondition)->orderBy('create_time DESC');
        if (isset($_GET['tag'])) {
            $query->andWhere(['like', 'tags', $_GET['tag']]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
            ]
        );
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $post = $this->findModel($id);
        $comment = $this->newComment($post);
        return $this->render(
            'view',
            [
                'model' => $post,
                'comment' => $comment,
            ]
        );
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Post();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render(
                'create',
                [
                    'model' => $model,
                ]
            );
        }
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render(
                'update',
                [
                    'model' => $model,
                ]
            );
        }
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->refresh();
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $query = Post::find()
            ->orderBy('create_time DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $countQuery = clone $query;
        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $countQuery->count()
        ]);

        $models = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        echo $this->render(
            'admin',
            [
                'models' => $models,
                'pagination' => $pagination,
                'dataProvider' => $dataProvider,
            ]
        );
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param Post $post
     * @return Comment
     */
    protected function newComment($post)
    {
        $comment = new Comment();
        if($comment->load(Yii::$app->request->post()) && $post->addComment($comment))
        {
            if($comment->status == Comment::STATUS_PENDING) {
                \Yii::$app->session->setFlash('commentSubmitted', 'Thank you for your comment. Your comment will be posted once it is approved.');
            }
            \Yii::$app->response->refresh();
        }
        return $comment;
    }
}