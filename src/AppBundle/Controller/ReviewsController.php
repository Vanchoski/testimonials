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
    public function index(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Review::class);
        $statistics = $repository->statistics();
//        dump($statistics);
//        die();
        $review = new Review();
        $form = $this->createForm(ReviewType::class,$review);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $review = $form->getData();

            return $this->redirectToRoute('');
        }

//        dump($form->createView());
//        die;

        return $this->render('reviews/index.html.twig',['statistics'=>$statistics,'form'=> $form->createView()]);
    }
}