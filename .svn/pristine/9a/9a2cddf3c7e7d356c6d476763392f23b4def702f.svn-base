<?php
namespace Zgh\FEBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Zgh\FEBundle\Entity\Comment;
use Zgh\FEBundle\Entity\Like;

interface CommentableInterface
{
    public function getObjectId();
    public function getObjectType();
    public function addComment(Comment $comment);
    public function setComments(ArrayCollection $comments);
    public function removeComment(\Zgh\FEBundle\Entity\Comment $comments);
    public function getComments();
}