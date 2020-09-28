<?php
namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use common\models\User;
use common\models\Settings;
use frontend\models\UploadForm;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\helpers\Json;

/**
 * Site controller
 */
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
                'only' => ['logout', 'signup', 'settings'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['settings'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return User::isUserAdmin(Yii::$app->user->identity->username);
                        }
                    ],
//                    [
//                        'actions' => ['about'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return User::isUserAdmin(Yii::$app->user->identity->username);
//                        }
//                    ],
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
     * @return mixed
     */
    public function actionIndex()
    {
//        return $this->render('index');
        if (Yii::$app->user->isGuest) {
            return $this->actionLogin();
        } else {
            return $this->actionCheckUser();
        }
    }

    public function actionCheckUser() {
        $role = Yii::$app->getUser()->getIdentity()->role;
        switch ($role) {
            case 10: // user
                return $this->render('index');
                break;
            case 20: // admin
                return $this->actionManageUsers();
                break;
            default:
                break;
        }
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays settings page.
     *
     * @return mixed
     */
    public function actionSettings()
    {
        $model = Settings::find()->all();
        return $this->render('settings', [
            'model' => $model,
        ]);
    }

    public function actionUpdatesettings()
    {
        $params =  Yii::$app->request->getBodyParams();
        foreach($params as $paramName => $paramValue) {
            $param = Settings::findByName($paramName);
            if($param) {
                $param->value = $paramValue;
                $param->save();
            }
        }
        Yii::$app->getSession()->setFlash('success', 'Settings changed successfully');
        return $this->redirect(['settings']);
    }

    /**
     * @return string
     */
    public function actionManageUsers()
    {

        $users = User::findAll(['role' => 10]);

        return $this->render('manage_users', ['users' => $users]);
    }

    public function actionDelete( $id ) {
        $user = User::findOne($id)->delete();
        if( $user ) {
            Yii::$app->getSession()->setFlash('danger', 'User deleted successfully');
            return $this->redirect(['index']);
        }
    }

    public function actionActivate( $id ) {
        $user = User::findOne($id);
        if( $user ) {
            $user->status = 10;
            $user->save();
            Yii::$app->getSession()->setFlash('success', 'User Activated successfully');
            return $this->redirect(['index']);
        }
    }

    public function actionDeactivate( $id ) {
        $user = User::findOne($id);
        if( $user ) {
            $user->status = 9;
            $user->save();
            Yii::$app->getSession()->setFlash('warning', 'User Deactivated successfully');
            return $this->redirect(['index']);
        }
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }

    public function actionUpload()
    {

        $model = new UploadForm();

        $model->file = UploadedFile::getInstance($model, 'file');

        return $model->upload();
    }

    public function actionImageDelete($name)
    {
        $user_id = Yii::$app->getUser()->getIdentity()->getId();
        $directory = Yii::getAlias('@frontend/web/img/temp') . DIRECTORY_SEPARATOR . $user_id;
        if (is_file($directory . DIRECTORY_SEPARATOR . $name)) {
            unlink($directory . DIRECTORY_SEPARATOR . $name);
        }

        $files = FileHelper::findFiles($directory);
        $output = [];
        foreach ($files as $file) {
            $fileName = basename($file);
            $path = '/img/temp/' . $user_id . DIRECTORY_SEPARATOR . $fileName;
            $output['files'][] = [
                'name' => $fileName,
                'size' => filesize($file),
                'url' => $path,
                'thumbnailUrl' => $path,
                'deleteUrl' => '?r=site/image-delete&name=' . $fileName,
                'deleteType' => 'POST',
            ];
        }
        $user_files = Yii::$app->getUser()->getIdentity()->files;
        $user = User::findOne($user_id);
        if($user) {
            $usr_files = [];
            if($user_files){
                $user->files = '';
                foreach (explode(';',$user_files) as $item){
                    if($item == $name){
                        continue;
                    }else{
                        array_push($usr_files,$item);
                    }
                }
                if($usr_files)
                    $user->files = implode(";",$usr_files);
                if($user->files_counter != 0)
                    $user->files_counter--;
                $user->save();
            }
        }
        return Json::encode($output);
    }

    public function actionViewUserFiles( $id ){
        $user = User::findOne($id);
        if( $user ) {
            $user_files = $user->files;
            return $this->render('view_user_files', ['user_id' => $id, 'user_files' => $user_files]);
        }else{
            Yii::$app->getSession()->setFlash('danger', 'User Not Found');
            return $this->redirect(['index']);
        }
    }
}
