<?php

namespace frontend\controllers;

use common\models\Servico;
use common\models\Relatorio;
use common\models\RelatorioSearch;
use common\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * RelatorioController implements the CRUD actions for Relatorio model.
 */
class RelatorioController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Relatorio models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RelatorioSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Relatorio model.
     * @param int $idRelatorio Id Relatorio
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Relatorio model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idservico)
    {

        $model = new Relatorio();
        // $model->id= Yii::$app->user->identity->id;


        $model->idServico = $idservico;
        $modelServico = Servico::findOne($idservico);
        $model->idDispositivo = $modelServico->idDispositivo;
        $user = User::findOne($modelServico->id);
        $model->autor = $user->username;
        $model->descricaoA = $modelServico->descricao;
        $model->id = Yii::$app->user->getId();

        if ($model->load(Yii::$app->request->post())) {

            $post = Yii::$app->request->post('Relatorio');
            //$model->listPecas = $post['listPecas'];
            $model->save();
            return $this->redirect(['view', 'id' => $model->idRelatorio]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Relatorio model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $idRelatorio Id Relatorio
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->idRelatorio]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Relatorio model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $idRelatorio Id Relatorio
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Relatorio model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $idRelatorio Id Relatorio
     * @return Relatorio the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Relatorio::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
