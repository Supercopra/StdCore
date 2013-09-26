<?php
namespace Core\Controller;

use Zend\Mvc\Controller\AbstractActionController;

abstract class AbstractController extends AbstractActionController
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $_em;

    /**
     * @var \Zend\Console\Adapter\AdapterInterface
     */
    private $_console = false;

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        if ($this->_em === null) {
            $this->_em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManger');
        }
        return $this->_em;
    }

    /**
     * @param string $entityNamespace
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository($entityNamespace)
    {
        return $this->_em->getRepository($entityNamespace);
    }

    /**
     * @return \Zend\Console\Adapter\AdapterInterface
     */
    public function getConsole()
    {
        if ($this->_console === false) {
            $this->_console = $this->getServiceLocator()->get('console');
            if (!$this->_console instanceof \Zend\Console\Adapter\AdapterInterface) {
                $this->_console = null;
            }
        }
        return $this->_console;
    }
}