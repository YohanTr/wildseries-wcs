<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CommentController extends AbstractController
{
    /**
     *
     * @Route("comment/{id}/edit", name="comment_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Comment $comment
     * @return Response
     */
    public function edit(Comment $comment, Request $request): Response
    {
        if (!($this->getUser() === $comment->getAuthor()) && !in_array('ROLE_ADMIN', $this->getUser()->getRoles()))  {
            // If not the owner, throws a 403 Access Denied exception
            throw new AccessDeniedException('Modifiable que par l\'auteur du commentaire');
        }
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('program_index');
        }

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("comment/{id}/", name="comment_delete", methods={"DELETE"})
     * @param Request $request
     * @param Comment $comment
     * @return Response
     */
    public function delete(Comment $comment, Request $request): Response
    {
        if (!($this->getUser() === $comment->getAuthor()) && !in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            // If not the owner, throws a 403 Access Denied exception
            throw new AccessDeniedException('Supprimable que par l\'auteur du commentaire');
        }

        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('program_index');
    }
}