<?php
namespace Zgh\FEBundle\Model\Utilities;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Entity\Partial\BasicInfo;

/**
 * @ORM\Entity
 * @ORM\Table(name="files")
 */
class File
{
    use BasicInfo;

    /**
     * @ORM\ManyToOne(targetEntity="FileContainer", inversedBy="files")
     */
    protected $container;

    private $file_dir = "files";

    protected $file_file;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $file_name;

    public function setFileDir($file_dir = "")
    {
        $this->file_dir = $file_dir;
    }

    public function getFileDir()
    {
        return $this->file_dir;
    }

    public function getUploadDir()
    {
        return 'uploads'."/".'files'."/".$this->file_dir;
    }

    public function getRootUploadDir()
    {
        return $_SERVER['DOCUMENT_ROOT']."/".$this->getUploadDir();
    }

    public function getWebPath()
    {
        return $this->file_name === null ? null : $this->getUploadDir()."/".$this->file_name;
    }

    public function getAbsolutePath()
    {
        return $this->file_name === null ? null : $this->getRootUploadDir()."/".$this->file_name;
    }

    /**
     * WARNING!! PreUpdate no fired since $file_file is not managed by Doctrine.
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
        if($this->file_file != null)
        {
            if($this->file_name != null)
            {
                if(file_exists($this->getAbsolutePath()))
                {
                    unlink($this->getAbsolutePath());
                }
            }

            $this->file_name = $this->file_file->getClientOriginalName();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if($this->file_file === null)
        {
            return;
        }

        if(!file_exists($this->getUploadDir()))
        {
            $old_umask = umask(0);
            mkdir($this->getUploadDir(),0777, true);
            umask($old_umask);
        }

        if($this->file_name)
        {
            $this->file_file->move($this->getUploadDir(), $this->file_name);
        }

        unset($this->file_file);
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
        }
    }

    public function setFileName($file_name)
    {
        $this->file_name = $file_name;
        return $this;
    }

    public function getFileName()
    {
        return $this->file_name;
    }

    public function setFileFile(UploadedFile $file)
    {
        $this->file_file = $file;
        return $this;
    }

    public function getFileFile()
    {
        return $this->file_file;
    }


    /**
     * Set container
     *
     * @param \Zgh\FEBundle\Entity\Utilities\FileContainer $container
     * @return File
     */
    public function setContainer(\Zgh\FEBundle\Entity\Utilities\FileContainer $container = null)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Get container
     *
     * @return \Zgh\FEBundle\Entity\Utilities\FileContainer
     */
    public function getContainer()
    {
        return $this->container;
    }
}
