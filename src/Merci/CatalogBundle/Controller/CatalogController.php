<?php

namespace Merci\CatalogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CatalogController extends Controller
{
    public function indexAction()
    {
        $products = $this->getDoctrine()
            ->getRepository('MerciCatalogBundle:Product')
            ->findAll();

        if (empty($products)) {
            throw $this->createNotFoundException('No products avaiable');
        }

        return $this->render('MerciCatalogBundle:Default:index.html.twig',
            array('products' => $products)
        );
    }

    public function productAction($id)
    {
        $product = $this->getDoctrine()
            ->getRepository('MerciCatalogBundle:Product')
            ->findOneById($id);

        if (!$product) {
            throw $this->createNotFoundException('No product found with id: ' . $id);
        }

        return $this->render('MerciCatalogBundle:Default:product.html.twig',
            array('product' => $product)
        );
    }

    public function searchAction()
    {
        $request = $this->getRequest();
        $find = $request->query->get('find');

        $products = $this->getDoctrine()
            ->getRepository('MerciCatalogBundle:Product')
            ->searchByName($find);

        if (empty($products)) {
            $this->get('session')->getFlashBag()->add(
                'notice', 'Nenhum resultado encontrado para pesquisa: '.$find
            );
            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render('MerciCatalogBundle:Default:index.html.twig',
            array('products' => $products, 'find' => $find)
        );
    }
}
