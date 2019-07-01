<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Review;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class ReviewsApiController extends Controller
{
    /**
     * @Route("/testimonials"),methods={GET}
     */

    public function filter(Request $request, SerializerInterface $serializer)
    {
        $repository = $this->getDoctrine()->getRepository(Review::class);
        $reviews = $repository->findReview($request->get('q'),$request->get('ratings'),$request->get('page'), $request->get('perPage'));
        $response = [
            'reviews' => $reviews,
            'total' => count($reviews),
            'per_page'=> $request->get('perPage'),
            'current_page' => $request->get('page')
        ];
        $jsonContent = $serializer->serialize($response, 'json');
        return new Response($jsonContent);
    }

    /**
     * @Route("/testimonials/add"),methods={POST}
     *
     */
    public function addReview(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Review::class);
        $repository->addReview($request->request->all());
        return new Response('Review create');
    }
}