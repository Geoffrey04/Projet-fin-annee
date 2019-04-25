<?php


namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;




/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 *
 */
class SearchPartsTitle extends Parts
{

    /**
     * @var string|null
     */
    private $search_title;

    /**
     * @return string|null
     */
    public function getSearchTitle(): ?string
    {
        return $this->search_title;
    }

    /**
     * @param string|null $search_title
     */
    public function setSearchTitle(?string $search_title): void
    {
        $this->search_title = $search_title;
    }



}