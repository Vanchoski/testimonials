<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Review;
use AppBundle\Form\ReviewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReviewsController extends Controller
{
    /**
     * @Route("/index")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Review::class);
        $statistics = $repository->statistics();
//        dump($statistics);
//        die();
        return $this->render('reviews/index.html.twig',['statistics'=>$statistics]);
    }

    public function newForm(Request $request)
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class,$review);

        $form->handleRequest($request);

    }
}