<?php
namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use common\models\Settings;
use yii\web\UploadedFile;

/**
 * Class UploadForm
 * @package frontend\models
 */
class UploadForm extends Model
{
    /**
     * @var UploadedFile|Null file attribute
     */
    public $file;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        $file_types = Settings::findByName('file_types')->value;

        return [
            [['file'], 'file', 'extensions' => $file_types],
        ];
    }

    public function upload()
    {
        $max_files_limit = Settings::findByName('file_number')->value;
        $file_types = Settings::findByName('file_types')->value;
        if ($this->validate()) {
            $user_id = Yii::$app->getUser()->id;
            $user_files = Yii::$app->getUser()->getIdentity()->files;
            $user = User::findOne($user_id);
            $user_files_counter = $user->files_counter;

            if($user_files_counter == $max_files_limit){
                return Json::encode([
                    'files' => [
                        [
                            'error' => 'Exceeded maximum file uploads. Limit is: ' . $max_files_limit
                        ],
                    ],
                ]);
            }

            $directory = Yii::getAlias('@frontend/web/img/temp') . DIRECTORY_SEPARATOR . $user_id . DIRECTORY_SEPARATOR;
            if (!is_dir($directory)) {
                FileHelper::createDirectory($directory);
            }

            $uid = uniqid(time(), true);
            $fileName = $uid . '.' . $this->file->extension;
            $filePath = $directory . $fileName;
            if ($this->file->saveAs($filePath)) {
                $path = '/img/temp/' . $user_id . DIRECTORY_SEPARATOR . $fileName;

                if($user) {
                    if($user_files)
                        $user->files .= ';'. $fileName;
                    else
                        $user->files = $fileName;
                    $user->files_counter++;
                    $user->save();
                }

                return Json::encode([
                    'files' => [
                        [
                            'name' => $fileName,
                            'size' => $this->file->size,
                            'url' => $path,
                            'thumbnailUrl' => $path,
                            'deleteUrl' => '?r=site/image-delete&name=' . $fileName,
                            'deleteType' => 'POST',
                        ],
                    ],
                ]);
            } else {
                return Json::encode([
                    'files' => [
                        [
                            'error' => 'File save failed!'
                        ],
                    ],
                ]);
            }

        } else {
            return Json::encode([
                'files' => [
                    [
                        'error' => 'Invalid file type. Allowed types are: ' . $file_types
                    ],
                ],
            ]);
        }
    }
}