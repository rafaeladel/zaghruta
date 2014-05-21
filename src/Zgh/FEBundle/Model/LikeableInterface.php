<?php
namespace Zgh\FEBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Zgh\FEBundle\Entity\Like;

interface LikeableInterface
{
    public function getObjectId();
    public function getObjectType();
    public function addLike(Like $like);
    public function setLikes(ArrayCollection $likes);
    public function removeLike(\Zgh\FEBundle\Entity\Like $likes);
    public function getLikes();
}