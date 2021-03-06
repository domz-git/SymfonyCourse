<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/post", name="post.")
 * @param PostRepository $postRepository
 * @return Response
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param PostRepository $postRepository
     * @return Response
     */
    public function index(PostRepository $postRepository)
    {

        $posts = $postRepository->findAll();


        return $this->render('post/index.html.twig', [
            'posts' =>$posts
        ]);
    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function create(Request $request){



        $post = new Post();

       $form = $this->createForm(PostType::class, $post);


       $form->handleRequest($request);
       if($form->isSubmitted()){
           $em = $this->getDoctrine()->getManager();
           $em->persist($post);
            $em->flush();

            return $this->redirect($this->generateUrl('post.index'));
       }



        return $this->render('post/create.html.twig', [

            'form'=>$form->createView()
        ]);



    }

    /**
     * @Route("/show/{id}", name="show")
     * @param Post $post
     * @return Response
     */
    public function show(Post $post){

        dump($post);

        return $this->render('post/show.html.twig', [

            'post'=>$post
        ]);
    }

    /**
     * @Route("/remove/{id}", name="remove")
     * @param Post $post
     * @return RedirectResponse
     */
    public function remove(Post $post){


    $em = $this->getDoctrine()->getManager();
    $em->remove($post);
    $em->flush();

    $this->addFlash('success', 'Post was removed!');

    return $this->redirect($this->generateUrl('post.index'));


    }
}
