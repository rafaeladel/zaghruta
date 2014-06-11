<?php
namespace Zgh\FEBundle\Service;

use Doctrine\ORM\EntityManagerInterface;

class RightSideManager
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->em = $entityManagerInterface;
    }

//    public function getRecommendedPeople()
//    {
//        return "";
//    }

    public function getRecommendedVendors()
    {
        $q = $this->em->createQuery("
                    select v, count(f) as f_count
                    from Zgh\FEBundle\Entity\User v
                    left join v.followers f
                    where v.roles like '%ROLE_VENDOR%'
                    order by f_count desc
                ");
        return $q->execute();
    }

    public function getNewVendors()
    {
        $q = $this->em->createQuery("
                    select v
                    from Zgh\FEBundle\Entity\User v
                    order by v.created_at desc
                ");
        return $q->execute();
    }


}