<?php


namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;




/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 *
 */
class SearchPartsAuthor extends Parts
{
    /**
     * @var string|null
     */
    private $search_author ;

    /**
     * @return mixed
     */
    public function getSearchAuthor()
    {
        return $this->search_author;
    }

    /**
     * @param mixed $search_author
     */
    public function setSearchAuthor($search_author): void
    {
        $this->search_author = $search_author;
    }
}