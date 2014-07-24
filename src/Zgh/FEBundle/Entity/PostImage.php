<?php
namespace Zgh\FEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;
use Zgh\FEBundle\Model\Utilities\Image;

/**
 * @ORM\Entity
 * @ORM\Table(name="posts_images")
 * @ORM\HasLifecycleCallbacks
 */
class PostImage extends Image
{
    use BasicInfo;



    /**
     * @ORM\OneToOne(targetEntity="Zgh\FEBundle\Entity\Post", inversedBy="image")
     */
    protected $post;


    /**
     * Set post
     *
     * @param \Zgh\FEBundle\Entity\Post $post
     * @return PostImage
     */
    public function setPost(\Zgh\FEBundle\Entity\Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \Zgh\FEBundle\Entity\Post 
     */
    public function getPost()
    {
        return $this->post;
    }
}
