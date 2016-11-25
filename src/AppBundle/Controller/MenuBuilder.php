<?php
/**
 * Created by PhpStorm.
 * User: DeadDance
 * Date: 25.11.2016
 * Time: 3:43
 */

namespace AppBundle\Controller;
use Knp\Menu\FactoryInterface;

class MenuBuilder
{
    private $factory;

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes(array('class' => 'nav'));
        $menu->addChild('Home', array('route' => 'blog_homepage'));
        $menu->addChild('Category', array('route' => 'blog_homepage'));
        // ... add more children

        return $menu;
    }

}
