<?php



namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;




/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 *
 */
class SearchUsername extends Users
{
    /**
     * @var string|null
     */
    private $search_username;


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







}