<?php


namespace App\Controller;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * @property ArticleRepository articleRepository
 */
class ArticlesController extends FOSRestController
{
    private $em;
    public function __construct(ArticleRepository $articleRepository, EntityManagerInterface $em) {
       $this->articleRepository = $articleRepository;
       $this->em = $em;
}

    /**
     * @Rest\View(serializerGroups={"article"})
     */
    public function getArticlesAction(){
        $article = $this->articleRepository->findAll();
        return $this->view($article);
    }
     // "get_articles"            [GET] /articles

    /**
     * @param Article $article
     * @return \FOS\RestBundle\View\View
     */
    public function getArticleAction(Article $article){
    return $this->view($article);
    }
    // "get_article"             [GET] /article/{id}

    /**
     * @Rest\Post("/articles")
     * @ParamConverter("article", converter="fos_rest.request_body")
     * @param Article $article
     * @return \FOS\RestBundle\View\View
     */
    public function postArticleAction(Article $article){
        $this->em->persist($article);
        $this->em->flush();
        return $this->view($article);
    }

    /**
     * @param Request $request
     * @param int $id
     */
    public function putArticleAction(Request $request, int $id){

        // $request->get('')
    }
    // "put_article"             [PUT] /articles/{id}
    public function deleteArticleAction(Article $article){
        $this->em->remove($article);
        $this->em->flush();
    }
    // "delete_article"          [DELETE] /articles/{id}}
}