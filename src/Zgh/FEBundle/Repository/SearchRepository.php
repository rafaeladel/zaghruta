<?php
namespace Zgh\FEBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Zgh\FEBundle\Entity\Search;

class SearchRepository extends EntityRepository
{
    public function getSearchResult(Search $search_obj, $user)
    {
        $search_type    = $search_obj->getSearchType();
        $search_text    = $search_obj->getSearchText();
        $search_sort    = $search_obj->getSearchSort();
        $search_filter  = $search_obj->getSearchFilter();

        $query = "select x from {$search_type} x where ";
        $query .= isset($user) ? "x.user = :user and " : "";
        $query .= "x.name = :name and ";
        $query .= "x.categories in :filter ";
        $query .= "order by x.created_at :order_type";

//        $q = $this->getEntityManager()->createQuery("
//                    select x from {$search_type} x
//                      where
//                          x.user = :user
//                        and
//                          x.name = :name
//                        and
//                          x.categories in :filter
//                        order by x.created_at :order_type
//            ")
        $q = $this->getEntityManager()->createQuery($query)
            ->setParameters([
                    "user" => $user,
                    "name" => $search_text,
                    "filter" => $search_filter,
                    "order_type" => $search_sort
                ])
        ;

        return $q->execute();
    }
}