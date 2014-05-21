<?php
namespace Zgh\FEBundle\ParamConverter;

use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SocialConverter implements ParamConverterInterface
{
    protected $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param Request $request
     * @param ParamConverter $configuration
     * @return bool
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $id = $request->request->get("id");
        $entity_type = $request->request->get("entity_type");
        $entity = null;
        if($entity_type == 0){
            $entity = $this->em->getRepository("ZghFEBundle:Post")->find($id);
        } else if($entity_type == 1){
            $entity = $this->em->getRepository("ZghFEBundle:Photo")->find($id);
        } else if($entity_type == 2){
            $entity = $this->em->getRepository("ZghFEBundle:Experience")->find($id);
        }

        if(!$entity)
        {
            throw new NotFoundHttpException();
        }

        $param = $configuration->getName();
        $request->attributes($param, $entity);

        return true;
    }

    /**
     * Checks if the object is supported.
     *
     * @param ParamConverter $configuration Should be an instance of ParamConverter
     *
     * @return boolean True if the object is supported, else false
     */
    public function supports(ParamConverter $configuration)
    {
        return 'Zgh\FEBundle\Model\LikeableInterface' == $configuration->getClass();
    }
}