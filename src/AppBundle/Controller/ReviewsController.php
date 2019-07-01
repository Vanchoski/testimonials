<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Review;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
}