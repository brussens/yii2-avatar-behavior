<?php
/**
 * Behavior for the transparent operations of avatar thumbnails
 *
 * @author Brusenskiy Dmitry <brussens@nativeweb.ru>
 * @link https://github.com/brussens/yii2-avatar-behavior
 * @copyright Brusenskiy Dmitry since 2015
 * @created 15.11.2015
 * @license http://opensource.org/licenses/MIT MIT
 * @version 0.1.0
 */

namespace brussens\behaviors;

use Yii;
use yii\base\Behavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\imagine\Image;
use yii\helpers\FileHelper;

class AvatarBehavior extends Behavior {

    /**
     * URL to uploads directory
     *
     * @var string
     */
    public $url = "/frontend/web/uploads";

    /**
     * Avatar attribute name
     *
     * @var string
     */
    public $attribute = 'avatar';

    /**
     * Directory for all uploads
     *
     * @var string
     */
    public $uploadDir = '@frontend/uploads';

    /**
     * No avatar base64 image
     *
     * @var string
     */
    public $noAvatar = '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';

    /**
     * Returns image URL
     *
     * @param null $width
     * @param null $height
     * @return string
     */
    public function getThumbUrl($width = null, $height = null) {
        if($this->owner->{$this->attribute}) {
            if(!$width && !$height) {
                return $this->getSourceFileUrl();
            }
            elseif(file_exists($this->getThumbFilePath($width, $height))) {
                return $this->getThumbFileUrl($width, $height);
            }
            else {
                $this->createThumb($width, $height);
            }
        }
        else {
            return 'data:image/png;base64,'.$this->noAvatar;
        }
        return 'data:image/png;base64,'.$this->noAvatar;
    }

    /**
     * Returns Html code of image
     *
     * @param null $width
     * @param null $height
     * @param array $params
     * @return string
     */
    public function getThumb($width = null, $height = null, $params = [])
    {
        $params = ArrayHelper::merge($params, [
            'width' => $width,
            'height' => $height
        ]);
        return Html::img($this->getThumbUrl(), $params);
    }

    /**
     * Create thumbnail and save it
     *
     * @param $width
     * @param $height
     * @return string
     */
    protected function createThumb($width, $height) {
        $result = Image::thumbnail($this->getSourceFilePath(), $width, $height)->save($this->getThumbFilePath($width, $height));
        if($result) {
            return $this->getThumbFileUrl($width, $height);
        }
        else {
            return $this->getSourceFileUrl();
        }
    }

    /**
     * Returns URL to source images directory
     *
     * @return string
     */
    protected function getSourceDirUrl()
    {
        return $this->url.'/'.$this->attribute;
    }

    /**
     * Returns URl to source image
     *
     * @return string
     */
    protected function getSourceFileUrl()
    {
        return $this->getSourceDirUrl().'/'.$this->owner->{$this->attribute};
    }

    /**
     * Returns URL to directory of thumbnails
     *
     * @return string
     */
    protected function getThumbsDirUrl()
    {
        return $this->getSourceDirUrl().'/thumbs';
    }

    /**
     * Returns URL to thumbnail
     *
     * @param $width
     * @param $height
     * @return string
     */
    protected function getThumbFileUrl($width, $height)
    {
        return $this->getThumbsDirUrl().'/'.$width.'x'.$height.'/'.$this->owner->{$this->attribute};
    }

    /**
     * Returns path to source images
     *
     * @return string
     */
    protected function getSourcePath()
    {
        return FileHelper::normalizePath(Yii::getAlias($this->getUploadDir())).DIRECTORY_SEPARATOR.$this->attribute;
    }

    /**
     * Returns path to source image
     *
     * @return string
     */
    public function getSourceFilePath()
    {
        return $this->getSourcePath().DIRECTORY_SEPARATOR.$this->owner->{$this->attribute};
    }

    /**
     * Returns path to thumbnails
     *
     * @return string
     */
    protected function getThumbsPath()
    {
       return $this->getSourcePath().DIRECTORY_SEPARATOR.'thumbs';
    }

    /**
     * Returns path to thumbnail image
     *
     * @param $width
     * @param $height
     * @return string
     */
    protected function getThumbFilePath($width, $height)
    {
        $path = $this->getThumbsPath().DIRECTORY_SEPARATOR.$width.'x'.$height;
        if(!is_dir($path)) {
            FileHelper::createDirectory($path);
        }
        return $path.DIRECTORY_SEPARATOR.$this->owner->{$this->attribute};
    }

    /**
     * Returns path to upload directory
     *
     * @return string
     */
    protected function getUploadDir()
    {
        return $this->uploadDir;
    }
}