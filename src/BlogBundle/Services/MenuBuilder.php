<?php
/**
 * Created by PhpStorm.
 * User: DeadDance
 * Date: 26.11.2016
 * Time: 5:52
 */
namespace BlogBundle\Services;

use Knp\Menu\FactoryInterface;
use Doctrine\ORM\EntityManager;

class MenuBuilder
{
    private $factory;
    private $em;


    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory,
                                EntityManager $em)
    {
        $this->factory = $factory;
        $this->em = $em;
    }

    public function createMainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Home', array('route' => 'homepage'));
        // ... add more children


        return $menu;
    }

}

