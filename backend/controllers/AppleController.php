<?php

namespace backend\controllers;

use common\models\Apple;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class AppleController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'eat' => ['POST'],
                    'fall' => ['POST'],
                    'generate' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $apples = Apple::find()->all();

        return $this->render('index', [
            'apples' => $apples,
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionGenerate()
    {
        $count = mt_rand(1, 5);
        for ($i = 0; $i < $count; $i++) {
            $apple = new Apple();
            $apple->save();
        }

        Yii::$app->session->setFlash('success', "Сгенерировано {$count} новых яблок.");
        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionFall($id)
    {
        $apple = $this->findModel($id);
        if ($apple->on_tree) {
            $apple->fallToGround();
            Yii::$app->session->setFlash('success', 'Яблоко упало на землю.');
        } else {
            Yii::$app->session->setFlash('warning', 'Яблоко уже на земле.');
        }
        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     * @param int $percent
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionEat($id)
    {
        $apple = $this->findModel($id);
        $percent = (int)Yii::$app->request->post('percent', 10);

        if ($percent <= 0 || $percent > 100) {
            Yii::$app->session->setFlash('error', 'Процент должен быть от 1 до 100.');
            return $this->redirect(['index']);
        }

        try {
            $apple->eat($percent);

            if ($apple->size == 0) {
                $apple->remove();
                Yii::$app->session->setFlash('success', 'Яблоко съедено полностью и удалено.');
            } else {
                Yii::$app->session->setFlash('success', "Съедено {$percent}% яблока.");
            }

        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     * @return Apple
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Apple::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
