<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use linslin\yii2\curl;
use yii\helpers\Json;
use app\models\UserForm;
use app\models\EntryForm;


class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionAjax()
    {
        $weather = new UserForm;
        $weather->name = Yii::$app->request->post('name');
        if ($weather->load(Yii::$app->request->post()) && $weather->validate()) 
        {
            $weatherstring = $weather->name;
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://api.openweathermap.org/data/2.5/weather?q=" . $weatherstring . "&appid=c438b94e9064ccfbb26538fc3701cb26",
                    CURLOPT_RETURNTRANSFER => 1
            ));

            $weatherresponse = curl_exec($curl);
            $weatherresponse = json_decode($weatherresponse, true);
            

            return $this->render('home', ['weather' => $weather, 'weatherresponse' => $weatherresponse]);
        }
        return $this->render('home', ['weather' => $weather]);
    }

    public function actionCurrencyapi()
    {
        $model = new EntryForm();
        $model->from = Yii::$app->request->post('from');
        $model->to = Yii::$app->request->post('to');
        $model->amount = Yii::$app->request->post('amount');

        if ($model->load(Yii::$app->request->post()) && $model->validate()) 
        {

            $string = $model->from."_".$model->to;

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://free.currconv.com/api/v7/convert?q=$string&compact=ultra&apiKey=052bde0bc323b2bebe7a",
                CURLOPT_RETURNTRANSFER => 1));
            $response = curl_exec($curl);

            curl_close($curl);

            $response = json_decode($response, true);

            $rate = $response[$string];
            $total = $rate * $model->amount;

            return $this->render('currency', ['model' => $model, 'response' => $response, 'total' => $total]);    
        }
        return $this->render('currency', ['model' => $model]);
    }
}
