<?php

namespace Zgh\FEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;

/**
 * @ORM\Entity(repositoryClass="Zgh\FEBundle\Repository\SearchRepository")
 * @ORM\Table(name="searches")
 * @ORM\HasLifecycleCallbacks
 */
class Search
{
    use BasicInfo;

    /**
     * @ORM\Column(type="text")
     */
    protected $search_text;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    protected $search_filter;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    protected $search_sort;

    /**
     * @ORM\Column(type="text")
     */
    protected $search_type;


    /**
     * Set search_text
     *
     * @param string $searchText
     * @return Search
     */
    public function setSearchText($searchText)
    {
        $this->search_text = $searchText;

        return $this;
    }

    /**
     * Get search_text
     *
     * @return string 
     */
    public function getSearchText()
    {
        return $this->search_text;
    }

    /**
     * Set search_filter
     *
     * @param array $searchFilter
     * @return Search
     */
    public function setSearchFilter($searchFilter)
    {
        $this->search_filter = $searchFilter;

        return $this;
    }

    /**
     * Get search_filter
     *
     * @return array 
     */
    public function getSearchFilter()
    {
        return $this->search_filter;
    }

    /**
     * Set search_sort
     *
     * @param array $searchSort
     * @return Search
     */
    public function setSearchSort($searchSort)
    {
        $this->search_sort = $searchSort;

        return $this;
    }

    /**
     * Get search_sort
     *
     * @return array 
     */
    public function getSearchSort()
    {
        return $this->search_sort;
    }

    /**
     * Set search_type
     *
     * @param string $searchType
     * @return Search
     */
    public function setSearchType($searchType)
    {
        $this->search_type = $searchType;

        return $this;
    }

    /**
     * Get search_type
     *
     * @return string 
     */
    public function getSearchType()
    {
        return $this->search_type;
    }
}
