<?php
// src/Sdz/BlogBundle/Controller/BlogController.php
 
namespace Sdz\BlogBundle\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sdz\BlogBundle\Entity\Article;
use Sdz\BlogBundle\Form\ArticleType;
use Sdz\BlogBundle\Form\ArticleEditType;
 
class BlogController extends Controller
{
  public function indexAction($page)
  {
    // Pour récupérer la liste de tous les articles : on utilise notre nouvelle méthode
    $articles = $this->getDoctrine()
                     ->getManager()
                     ->getRepository('SdzBlogBundle:Article')
                     ->getArticles(2, $page);
 
    // L'appel de la vue ne change pas
    return $this->render('SdzBlogBundle:Blog:index.html.twig', array(
      'articles'    => $articles,
      'page'        => $page,
      'nombrePage' => ceil(count($articles)/2)
    ));
  }
 
  public function voirAction(Article $article)
  {
    // À ce stade, la variable $article contient une instance de la classe Article
    // Avec l'id correspondant à l'id contenu dans la route !
 
    // On récupère ensuite les articleCompetence pour l'article $article
    // On doit le faire à la main pour l'instant, car la relation est unidirectionnelle
    // C'est-à-dire que $article->getArticleCompetences() n'existe pas !
    $listeArticleCompetence = $this->getDoctrine()
                                   ->getManager()
                                   ->getRepository('SdzBlogBundle:ArticleCompetence')
                                   ->findByArticle($article->getId());
 
    return $this->render('SdzBlogBundle:Blog:voir.html.twig', array(
      'article'                 => $article,
      'listeArticleCompetence'  => $listeArticleCompetence
    ));
  }
 
  public function ajouterAction()
  {
    $article = new Article;
    $article->setPublication(false);

    $form = $this->createForm(new ArticleType, $article);

    $request = $this->get('request');
    if ($request->getMethod() == 'POST') {
      $form->bind($request);

      if ($form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        return $this->redirect($this->generateUrl('sdzblog_accueil'));
      }
    }

    return $this->render('SdzBlogBundle:Blog:ajouter.html.twig', array(
      'form' => $form->createView(),
    ));
  }
 
  public function modifierAction(Article $article)
  {
    // On utilise le ArticleEditType
    $form = $this->createForm(new ArticleEditType(), $article);

    $request = $this->getRequest();

    if ($request->getMethod() == 'POST') {
      $form->bind($request);

      if ($form->isValid()) {
        // On enregistre l'article
        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        // On définit un message flash
        $this->get('session')->getFlashBag()->add('info', 'Article bien modifié');

        return $this->redirect($this->generateUrl('sdzblog_voir', array('id' => $article->getId())));
      }
    }

    return $this->render('SdzBlogBundle:Blog:modifier.html.twig', array(
      'form'    => $form->createView(),
      'article' => $article
    ));
  }
 
  public function supprimerAction($id)
  {
    // On récupère l'EntityManager
    $em = $this->getDoctrine()
               ->getEntityManager();
 
    // On récupère l'entité correspondant à l'id $id
    $article = $em->getRepository('SdzBlogBundle:Article')
                  ->find($id);
     
    // Si l'article n'existe pas, on affiche une erreur 404
    if ($article == null) {
      throw $this->createNotFoundException('Article[id='.$id.'] inexistant');
    }
 
    if ($this->get('request')->getMethod() == 'POST') {
      // Si la requête est en POST, on supprimera l'article
       
      $this->get('session')->getFlashBag()->add('info', 'Article bien supprimé');
 
      // Puis on redirige vers l'accueil
      return $this->redirect( $this->generateUrl('sdzblog_accueil') );
    }
 
    // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
    return $this->render('SdzBlogBundle:Blog:supprimer.html.twig', array(
      'article' => $article
    ));
  }
 
  public function menuAction($nombre)
  {
    $liste = $this->getDoctrine()
                  ->getManager()
                  ->getRepository('SdzBlogBundle:Article')
                  ->findBy(
                    array(),          // Pas de critère
                    array('date' => 'desc'), // On trie par date décroissante
                    $nombre,         // On sélectionne $nombre articles
                    0                // À partir du premier
                  );
 
    return $this->render('SdzBlogBundle:Blog:menu.html.twig', array(
      'liste_articles' => $liste // C'est ici tout l'intérêt : le contrôleur passe les variables nécessaires au template !
    ));
  }
}