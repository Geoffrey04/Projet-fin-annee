<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;




/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 *
 */
class SearchPartsGroup extends Parts
{
    /**
     * @var string|null
     */
    private $search_group;

    /**
     * @return string|null
     */
    public function getSearchGroup(): ?string
    {
        return $this->search_group;
    }

    /**
     * @param string|null $search_group
     */
    public function setSearchGroup(?string $search_group): void
    {
        $this->search_group = $search_group;
    }

}