<?php


namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class Pagination
{
    private $entityClass;
    private $limite = 10;
    private $currentPage = 1;
    private $manager;

    /**
     * Pagination constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
    }

    /**
     * @return false|float
     */
    public function getPages(){
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());

        $pages = ceil($total / $this->limite);
        return $pages;
    }

    public function getData(){
        // 1) Calcul de loffset
        $offset = $this->currentPage * $this->limite - $this->limite;
        //2) Demander au repository de trouver les éléments
        if (empty($this->entityClass)){
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous 
            devons travailler. Vous pouvez utiliser la méthode setEntityCalass() de votre sercicePagination.");
        }
        $repos = $this->manager->getRepository($this->entityClass);
        $data = $repos->findBy([],[], $offset, $this->limite);
        return $data;
    }

    /**
     * Pagination constructor.
     * @param $entityClass
     * @return $this
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;

        return $this;
    }
    /**
     * @param $limite
     * @return $this
     */
    public function setLimite($limite){
        $this->limite = $limite;
        return $this;
    }

    /**
     * @param $page
     * @return $this
     */
    public function setPage($page){
        $this->currentPage = $page;
        return $this;
    }

    public function getEntityClass(){
        return $this->entityClass;
    }
    public function getLImite(){
        return $this->limite;
    }

    /**
     * @return int
     */
    public function getPage(){
        if (empty($this->entityClass)){
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous 
            devons travailler. Vous pouvez utiliser la méthode setEntityClass() de votre sercicePagination.");
        }
        return $this->currentPage;
    }

}