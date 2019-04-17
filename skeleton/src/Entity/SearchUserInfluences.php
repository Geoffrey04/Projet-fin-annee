<?php


namespace App\Entity;




class SearchUserInfluences extends Users
{

    /**
     * @var string | null
     */
    private $search_influence;

    /**
     * @return string|null
     */
    public function getSearchInfluence(): ?string
    {
        return $this->search_influence;
    }

    /**
     * @param string|null $search_influence
     */
    public function setSearchInfluence(?string $search_influence): void
    {
        $this->search_influence = $search_influence;
    }
}