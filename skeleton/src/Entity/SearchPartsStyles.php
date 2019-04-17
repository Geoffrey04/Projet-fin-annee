<?php


namespace App\Entity;


class SearchPartsStyles extends Parts
{

    /**
     * @var string|null
     */
    private $search_styles;

    /**
     * @return string|null
     */
    public function getSearchStyles(): ?string
    {
        return $this->search_styles;
    }

    /**
     * @param string|null $search_styles
     */
    public function setSearchStyles(?string $search_styles): void
    {
        $this->search_styles = $search_styles;
    }

}