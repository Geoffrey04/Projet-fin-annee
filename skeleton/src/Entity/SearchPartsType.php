<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;




/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 *
 */
class SearchPartsType extends Parts
{

    /**
     * @var string|null
     */
    private $search_type;

    /**
     * @return mixed
     */
    public function getSearchType()
    {
        return $this->search_type;
    }

    /**
     * @param mixed $search_type
     */
    public function setSearchType($search_type): void
    {
        $this->search_type = $search_type;
    }


}