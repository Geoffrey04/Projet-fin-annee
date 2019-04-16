<?php


namespace App\Entity;


class SearchUser extends Users
{

    /**
     * @var string|null
     */
    private $search_username;

    /**
     * @var string | null
     */
    private $search_style;

    /**
     * @var string | null
     */
    private $search_influence;


    /**
     * @return string|null
     */
    public function getSearchUsername(): ?string
    {
        return $this->search_username;
    }

    /**
     * @param string|null $search_username
     */
    public function setSearchUsername(?string $search_username): void
    {
        $this->search_username = $search_username;
    }

    /**
     * @return string|null
     */
    public function getSearchStyle(): ?string
    {
        return $this->search_style;
    }

    /**
     * @param string|null $search_style
     */
    public function setSearchStyle(?string $search_style): void
    {
        $this->search_style = $search_style;
    }

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