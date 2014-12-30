<?php
namespace Zgh\FEBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;

class DeleteManager
{
    protected $em;
    protected $securityContext;

    public function __construct(
        SecurityContextInterface $securityContextInterface,
        EntityManagerInterface $entityManagerInterface
    ) {
        $this->em = $entityManagerInterface;
        $this->securityContext = $securityContextInterface;
    }

    public function delete($entity)
    {
        $current_user = $this->securityContext->getToken()->getUser();
        $entity_user = $entity->getUser();
        if ($current_user == null || $current_user->getId() != $entity_user->getId()) {
            throw new AccessDeniedException;
        }
        $this->em->remove($entity);
        $this->em->flush();
        return true;
    }
}