<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19/01/19
 * Time: 13:04
 */

namespace App\Controller;

use App\Entity\BlogPost;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

/**
 * Class BlogController
 * @package App\Controller
 * @Route("/blog")
 */
class BlogController extends AbstractController
{

    /**
     * @param int $page
     * @return JsonResponse
     * @Route("/{page}", name="blog_list", defaults={"page": 5}, requirements={"page"="\d+"})
     */
    public function list($page, Request $request)
    {
        $limit = $request->get("limit", 10);
        $repository = $this->getDoctrine()->getRepository(BlogPost::class);
        $items = $repository->findAll();

        // return new JsonResponse(
        return $this->json(
            [
                'page' => $page,
                'limit' => $limit,
                'data' => array_map(function (BlogPost $item){
                    return $this->generateUrl('blog_by_slug', ['slug' => $item->getSlug()]);
                }, $items)
            ]
        );
    }

    /**
     * @param int $page
     * @return JsonResponse
     * @Route("/post/{id}", name="blog_by_id", requirements={"id"="\d+"}, methods={"GET"})
     * @ParamConverter("post", class="App:BlogPost")
     */
    public function post($post)
    {

        // return new JsonResponse(
        // It's the same as doing find($id) on repository
        return $this->json($post
        );
    }




    /**
     * @param string $slug
     * @return JsonResponse
     * @Route("/post/{slug}", name="blog_by_slug", methods={"GET"})
     * @ParamConverter("post", class="App:BlogPost", options={"mapping": {"slug": "slug"}})
     * The below annotation is not required when $post is typehinted witvh BlogPost
     * and route parameter name matches any field on the BlogPost entity
     */
    public function postBySlug(BlogPost $post)
    {
        // return new JsonResponse(
        // $repository = $this->getDoctrine()->getRepository(BlogPost::class)->findOneBy(['slug' => $slug])
        // It's the same as doing findByOne(['slug' => contents of {slug}]) on repository
        return $this->json($post);

    }

    /**
     * @param Request $request
     * @Route("/add", name="blog_add", methods={"POST"})
     */
    public function add(Request $request)
    {
        /** @var Serializer $serialize */
        $serialize = $this->get('serializer');

        $blogPost = $serialize->deserialize($request->getContent(), BlogPost::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($blogPost);
        $em->flush();

        return $this->json($blogPost);
    }

    /**
     * @param BlogPost $post
     * @Route("/post/{id}", name="blog_delete", requirements={"id":"\d+"}, methods={"DELETE"})
     */
    public function delete(BlogPost $post)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}