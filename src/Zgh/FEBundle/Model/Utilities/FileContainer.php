<?php
namespace Zgh\FEBundle\Model\Utilities;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Entity\Partial\BasicInfo;

/**
 * @ORM\Entity
 * @ORM\Table(name="files_container")
 */
class FileContainer
{
    use BasicInfo;

    /**
     * @ORM\OneToMany(targetEntity="File", mappedBy="container")
     */
    protected $files;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add files
     *
     * @param \Zgh\FEBundle\Entity\Utilities\File $files
     * @return FileContainer
     */
    public function addFile(\Zgh\FEBundle\Entity\Utilities\File $files)
    {
        $this->files[] = $files;

        return $this;
    }

    /**
     * Remove files
     *
     * @param \Zgh\FEBundle\Entity\Utilities\File $files
     */
    public function removeFile(\Zgh\FEBundle\Entity\Utilities\File $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFiles()
    {
        return $this->files;
    }

}
