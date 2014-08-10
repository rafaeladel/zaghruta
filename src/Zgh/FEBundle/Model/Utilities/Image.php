<?php
namespace Zgh\FEBundle\Model\Utilities;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
class Image
{
    protected $cat_dir = "";
    protected $thumb_dir = null;

    protected $thumb_h = 100;
    protected $thumb_w = 150;

    /**
     * @Assert\Image
     */
    protected $image_file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image_name;

    protected $crop_coordinates;

    protected $temp_img_size;

    public function getUploadDir()
    {
        return 'uploads'."/".'images'."/".$this->cat_dir;
    }

    public function getThumbUploadDir()
    {
        return $this->getUploadDir()."/".$this->thumb_dir;
    }

    public function getRootUploadDir()
    {
        return __DIR__."/../../../../../web/".$this->getUploadDir();
    }

    public function getThumbRootUploadDir()
    {
        return __DIR__."/../../../../../web/".$this->getThumbUploadDir();
    }

    public function getWebPath()
    {
        return $this->image_name === null ? null : $this->getUploadDir()."/".$this->image_name;
    }

    public function getThumbWebPath()
    {
        return $this->image_name === null ? null : $this->getThumbUploadDir()."/".$this->image_name;
    }

    public function getAbsolutePath()
    {
        return $this->image_name === null ? null : $this->getRootUploadDir()."/".$this->image_name;
    }

    public function getThumbAbsolutePath()
    {
        return $this->image_name === null ? null : $this->getThumbRootUploadDir()."/".$this->image_name;

    }

    /**
     * WARNING!! PreUpdate not fired since $file is not managed by Doctrine.
     * SOLUTION: use PostLoad() Event.
     * @ORM\PostLoad()
     */
    public function postLoad()
    {
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if($this->image_file != null)
        {
            if($this->image_name != null)
            {
                if(file_exists($this->getAbsolutePath()))
                {
                    unlink($this->getAbsolutePath());
                }
            }
            $this->image_name = uniqid().".jpeg";
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if($this->image_file === null)
        {
            return;
        }

        if(!file_exists($this->getUploadDir()))
        {
            $old_umask = umask(0);
            mkdir($this->getUploadDir(),0777, true);
            umask($old_umask);
        }

        if($this->thumb_dir)
        {
            if(!file_exists($this->getThumbUploadDir()))
            {
                $old_umask = umask(0);
                mkdir($this->getThumbUploadDir(),0775, true);
                umask($old_umask);
            }
        }


        $this->image_file->move($this->getUploadDir(), $this->image_name);



        if(isset($this->crop_coordinates))
        {
            list($original_width, $original_height, $img_type) = getimagesize($this->getAbsolutePath());
            $src = null;
            switch($img_type)
            {
                case IMAGETYPE_JPEG:
                    $src = \imagecreatefromjpeg($this->getAbsolutePath());
                    break;
                case IMAGETYPE_PNG:
                    $src = \imagecreatefrompng($this->getAbsolutePath());
                    break;
                case IMAGETYPE_GIF:
                    $src = \imagecreatefromgif($this->getAbsolutePath());
                    break;
                default:
                    $src = null;
                    break;
            }

            if($src)
            {

                if($this->temp_img_size["temp_width"] == 0)
                {
                    $dst = imagecreatetruecolor($this->crop_coordinates["width"], $this->crop_coordinates["height"]);
                    \imagecopyresampled($dst, $src, 0, 0, 0, 0, $this->crop_coordinates["width"], $this->crop_coordinates["height"], $original_width, $original_height);
                }
                else
                {

                    /*
                     *  If cropping wrapper is smaller than the actual image, getting right original coordinates:
                     *   original_height   :$original_height
                     *   original_width    :$original_width
                     *   original_x        : width_ratio * temp_x
                     *   original_y        : height_ratio * temp_y
                     *   org_crop_height   : height_ratio * temp_crop_height
                     *   org_crop_width    : width_ratio * temp_crop_width
                     *
                     *   temp_height       : $this->temp_img_size["temp_height"]
                     *   temp_width        : $this->temp_img_size["temp_width"]
                     *   temp_x            : $this->crop_coordinates["x"]
                     *   temp_y            : $this->crop_coordinates["y"]
                     *   temp_crop_height  : $this->crop_coordinates["height"]
                     *   temp_crop_width   : $this->crop_coordinates["width"]
                    */

                    $width_ratio = $original_width / $this->temp_img_size["temp_width"];
                    $height_ratio = $original_height / $this->temp_img_size["temp_height"];

                    $original_x = $width_ratio * $this->crop_coordinates["x"];
                    $original_y = $height_ratio * $this->crop_coordinates["y"];

                    $org_crop_width = $width_ratio * $this->crop_coordinates["width"];
                    $org_crop_height = $height_ratio * $this->crop_coordinates["height"];


                    $dst = imagecreatetruecolor($org_crop_width, $org_crop_height);


                    $tmp_org = imagecreatetruecolor($original_width, $original_height);
                    $white = imagecolorallocate($tmp_org,  255, 255, 255);
                    imagefilledrectangle($tmp_org, 0, 0, $original_width, $original_height, $white);
                    \imagecopyresampled($tmp_org, $src, 0, 0, 0, 0, $original_width, $original_height, $original_width, $original_height);
                    \imagecopyresampled($dst, $tmp_org, 0, 0, $original_x, $original_y, $org_crop_width, $org_crop_height, $org_crop_width, $org_crop_height);
                }
                \imageinterlace($dst, true);
                \imagejpeg($dst, $this->getAbsolutePath(), 80);
            }
        }
        else
        {
            list($original_width, $original_height, $img_type) = getimagesize($this->getAbsolutePath());
            $src = null;
            switch($img_type)
            {
                case IMAGETYPE_JPEG:
                    $src = \imagecreatefromjpeg($this->getAbsolutePath());
                    break;
                case IMAGETYPE_PNG:
                    $src = \imagecreatefrompng($this->getAbsolutePath());
                    break;
                case IMAGETYPE_GIF:
                    $src = \imagecreatefromgif($this->getAbsolutePath());
                    break;
                default:
                    $src = null;
                    break;
            }

            if($src)
            {
                $dst = imagecreatetruecolor($original_width, $original_height);
                $white = imagecolorallocate($dst,  255, 255, 255);
                imagefilledrectangle($dst, 0, 0, $original_width, $original_height, $white);
                \imagecopy($dst, $src, 0, 0, 0, 0, $original_width, $original_height);
                \imageinterlace($dst, true);
                \imagejpeg($dst, $this->getAbsolutePath(), 80);
            }
        }

        if($this->thumb_dir)
        {
            list($original_width, $original_height, $img_type) = getimagesize($this->getAbsolutePath());
            $src = null;
            switch($img_type)
            {
                case IMAGETYPE_JPEG:
                    $src = \imagecreatefromjpeg($this->getAbsolutePath());
                    break;
                case IMAGETYPE_PNG:
                    $src = \imagecreatefrompng($this->getAbsolutePath());
                    break;
                case IMAGETYPE_GIF:
                    $src = \imagecreatefromgif($this->getAbsolutePath());
                    break;
                default:
                    $src = null;
                    break;
            }

            $sourceRatio = $original_width / $original_height;
            $targetRatio = $this->thumb_w / $this->thumb_h;

            if ( $sourceRatio < $targetRatio ) {
                $scale = $original_width/ $this->thumb_w;
            } else {
                $scale = $original_height / $this->thumb_h;
            }

            $resizeWidth = (int)($original_width / $scale);
            $resizeHeight = (int)($original_height / $scale);

            $cropLeft = (int)(($resizeWidth - $this->thumb_w) / 2);
            $cropTop = (int)(($resizeHeight - $this->thumb_h) / 2);

            $dst = imagecreatetruecolor($this->thumb_w, $this->thumb_h);
            $white = imagecolorallocate($dst,  255, 255, 255);
            imagefilledrectangle($dst, 0, 0, $original_width, $original_height, $white);
            \imagecopyresampled($dst, $src, 0, 0, $cropLeft, $cropTop, $resizeWidth, $resizeHeight, $original_width, $original_height);

            \imageinterlace($dst, true);
            \imagejpeg($dst, $this->getThumbAbsolutePath(), 80);
        }

        unset($this->image_file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if($this->getAbsolutePath() !== null){
            if(file_exists($this->getAbsolutePath()))
            {
                unlink($this->getAbsolutePath());
            }

            if($this->thumb_dir)
            {
                if(file_exists($this->getThumbAbsolutePath()))
                {
                    unlink($this->getThumbAbsolutePath());
                }
            }
        }
    }

    public function setImageFile($img_file)
    {
        $this->image_file = $img_file;
        return $this;
    }

    public function getImageFile()
    {
        return $this->image_file;
    }

    public function setImageName($img_name)
    {
        $this->image_name = $img_name;
        return $this;
    }

    public function getImageName()
    {
        return $this->image_name;
    }

    public function getTempImgSize()
    {
        return $this->temp_img_size;
    }

    public function setTempImgSize($img_size)
    {
        $this->temp_img_size = $img_size;
        return $this;
    }

    public function getCropCoordinates()
    {
        return $this->crop_coordinates;
    }

    public function setCropCoordinates($coordinates)
    {
        $this->crop_coordinates = $coordinates;
        return $this;
    }

}
