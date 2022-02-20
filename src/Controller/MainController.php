<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use App\Entity\Product;
use App\Entity\User;
use App\Form\ProductFormType;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;

class MainController extends AbstractController
{
    private $twig;
    private $entityManager;

    public function __construct(Environment $twig, EntityManagerInterface $entityManager)
    {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
    }

    #[Route('/main', name: 'main')]
    public function index(UserRepository $userRepository, ProductRepository $productRepository): Response
    {
        return new Response($this->twig->render('main/index.html.twig', [
            'users' => $userRepository->findAll(),
            'products' => $productRepository->findAll(),
            //dump($userRepository->findAll()),
            //dump($productRepository->findAll()),
        ]));
    }


    #[Route('/user', name: 'add_user')]
    public function addUser(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->redirectToRoute('main');
        }
        return $this->render('main/user.html.twig', [
            'user_form' => $form->createView()
        ]);
    }

    #[Route('/product', name: 'add_product')]
    public function addProduct(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($product);
            $this->entityManager->flush();
            return $this->redirectToRoute('main');
        }
        return $this->render('main/product.html.twig', [
            'product_form' => $form->createView()
        ]);
    }


}




