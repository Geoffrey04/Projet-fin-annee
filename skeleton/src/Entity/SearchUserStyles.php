<?php


namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;


use Doctrine\ORM\Mapping as ORM;




/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 *
 */
class SearchUserStyles extends Users
{
    /**
     * @var string | null
     * @Assert\Length(min="3" , minMessage = "Vous avez moins que {{ limit }} caractères.")
     */
    private $search_style;


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
}