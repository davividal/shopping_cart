<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Doctrine\Common\Collections\Collection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{
    /**
     * @Route("/dashboard/products", name="products")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        /** @var Collection $products */
        $products = $this->getUser()->getItems();

        $total = array_reduce(
            $products->toArray(),
            function ($sum, Product $item) {
                return $sum += (float)$item->getPrice() * $item->getQuantity();
            }
        );

        return $this->render(
            'default/product_list.html.twig',
            [
                'products' => $products,
                'total' => $total,
            ]
        );
    }

    /**
     * @Route("/dashboard/insert-products", name="insert-products")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function insertProducts(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add(
                'products',
                FileType::class,
                [
                    'label' => 'Produtos',
                ]
            )
            ->add('save', SubmitType::class, ['label' => 'Inserir'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $productsFile */
            $productsFile = $form->getData()['products'];
            $fh = $productsFile->openFile('r');

            $products = explode("\n", trim($fh->fread($fh->getSize())));

            foreach ($products as $product) {
                $product = explode("\t", $product);

                if (3 !== count($product)) {
                    continue;
                }

                $productEntity = new Product();
                $productEntity->setName($product[0]);
                $productEntity->setPriceFromString($product[1]);
                $productEntity->setQuantity($product[2]);
                $productEntity->setUser($this->getUser());

                $this->getDoctrine()->getManager()->persist($productEntity);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('products');
        }

        return $this->render(
            'default/insert_products.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/api/insert-product", name="insert-product")
     * @Method({"POST"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function insertProduct(Request $request)
    {
        $rawBody = $request->getContent();
        $body = json_decode($rawBody);

        try {
            $productName = $body->product_name;
            $productPrice = $body->product_price;
            $productQuantity = $body->product_quantity;
        } catch (ContextErrorException $e) {
            return $this->json(
                [
                    'error' => 'Formato inesperado',
                    'expected' => '"{\n\t\"product_name\": \"Carne de segunda\",\n\t\"product_price\": \"R$960,00\",\n\t\"product_quantity\": 2\n}"',
                ],
                422
            );
        }

        $product = new Product();
        $product->setName($productName);
        $product->setQuantity($productQuantity);
        $product->setPriceFromString($productPrice);
        $product->setUser($this->getUser());

        $this->getDoctrine()->getManager()->persist($product);
        $this->getDoctrine()->getManager()->flush();

        return $this->json(['message' => 'Produto inserido com sucesso!']);
    }

    /**
     * @Route("/api/get-products", name="get-products")
     * @Method({"GET"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getProducts()
    {
        $user = $this->getUser();
        return $this->json($user->getItems()->toArray());
    }
}