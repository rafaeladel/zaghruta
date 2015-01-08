<?php
/**
 * Created by PhpStorm.
 * User: Ahmed_elashqar
 * Date: 1/8/2015
 * Time: 6:08 PM
 */

namespace Zgh\FEBundle\Entity;


use Proxies\__CG__\Zgh\FEBundle\Entity\Post;

class PostsFeeds extends Post{
    protected $user_id;

    /**
     * @return mixed
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * @param mixed $fullname
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
    protected $fullname;

} 