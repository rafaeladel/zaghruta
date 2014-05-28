<?php

namespace Zgh\FEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;

/**
 * @ORM\Entity
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
}
