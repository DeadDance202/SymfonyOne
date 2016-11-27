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

    public function createMainMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', '"nav navbar-nav');

        $menu->addChild(
            'category',
            array('label' => 'Guitars'))->setAttribute('dropdown', true);

        $listCategories = $this->em->getRepository('BlogBundle:Category')->findBy(array('catalog'=>1));
        foreach ($listCategories as $category) {
            $menu['category']->addChild(
                'category' . $category->getName(),
                array('label' => $category->getName(),
                    'route' => 'category',
                    'routeParameters' => array('id'=>$category->getId())
                )
            );
        }
        return $menu;
    }

}

