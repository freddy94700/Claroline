<?php

namespace Claroline\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Claroline\CoreBundle\Entity\Workspace\AbstractWorkspace;

class UserRepository extends EntityRepository
{
    public function getUsersByUsernameList(array $usernames)
    {
        $nameList = array_map(
            function($name)
            { 
                return "'{$name}'"; 
            }, 
            $usernames
        );
        $nameList = implode(', ', $nameList);
        $dql = "
            SELECT u FROM Claroline\CoreBundle\Entity\User u
            WHERE u.username IN ({$nameList})
            ORDER BY u.username
        ";
        $query = $this->_em->createQuery($dql);
        
        return $query->getResult();
    }
    
    public function getUsersOfWorkspace(AbstractWorkspace $workspace)
    {
       $dql ="
            SELECT u FROM Claroline\CoreBundle\Entity\User u  
            JOIN u.workspaceRoles wr JOIN wr.workspace w WHERE w.id = '{$workspace->getId()}'"
       ;   
        $query = $this->_em->createQuery($dql);
        
        return $query->getResult();   
    }
    
    //doctrine doesn't have any DQL LIMIT clause.
    public function getLazyUnregisteredUsersOfWorkspace(AbstractWorkspace $workspace, $numberIteration, $userAmount)
    {
       $offset=$numberIteration*$userAmount;

       $dql="SELECT u FROM Claroline\CoreBundle\Entity\User u WHERE u NOT IN 
            (
                 SELECT us FROM Claroline\CoreBundle\Entity\User us
                 JOIN us.workspaceRoles wr JOIN wr.workspace w WHERE w.id = '{$workspace->getId()}'
             )";

       $query = $this->_em->createQuery($dql);
       $query->setMaxResults($userAmount);
       $query->setFirstResult($offset);
        
       return $query->getResult();       
    }
    
    public function getUnregisteredUsersOfWorkspace(AbstractWorkspace $workspace)
    {
             $dql = "
            SELECT u FROM Claroline\CoreBundle\Entity\User u WHERE u NOT IN (
            SELECT us FROM Claroline\CoreBundle\Entity\User us
            JOIN us.workspaceRoles wr JOIN wr.workspace w WHERE w.id = '{$workspace->getId()}')
        ";    
        $query = $this->_em->createQuery($dql);
        
        return $query->getResult();   
    }
}