<?php

namespace App\Controller;

use App\Entity\PostPosts;
use App\Repository\PostPostsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostPostsController extends AbstractController
{
    #[Route('/lista', name: 'app_post')]
    public function index(PostPostsRepository $postPosts): Response
    {

        return $this->renderForm('post/show.html.twig',[
            'postPosts'=>$postPosts->findAll()
        ]);
    }

    #[Route('/lista/delete/{post}', name: 'app_post_delete')]
    public function delete(PostPosts $post, PostPostsRepository $posts){
        
        $posts->remove($post, true);

        return $this->redirectToRoute('app_post');
    }
    #[Route('/')]
    public function redire(){
        return $this->redirectToRoute('app_post_login');
    }
}
