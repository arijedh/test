<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Repository\EtuadiantRepository;
use App\Entity\Etuadiant;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\EtuType;

class EtuadiantController extends AbstractController
{
    #[Route('/etuadiant', name: 'app_etuadiant')]
    public function index(): Response
    {
        return $this->render('etuadiant/index.html.twig', [
            'controller_name' => 'EtuadiantController',
        ]);
    }

   
   
    #[Route('/affiche',name:'app_affiche')]
    public function afficher(EtuadiantRepository $ar):Response
    {
        $list=$ar->findAll();
        return $this->render('etuadiant/affiche.html.twig',['list'=>$list]);
    }
    #[Route('/ajouter',name:'etud_app')]
    public function ajouter(ManagerRegistry $doctrine, Request $request):response
    {
        $etu=new Etuadiant();
        $form = $this->createForm(EtuType::class, $etu);
          $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
        $em=$doctrine->getManager(); 
        $em->persist($etu); 
        $em->flush();
        return $this->redirectToRoute('affiche_app');
        }
             return $this->render('etuadiant/ajouter.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[route('/delete/{id}',name:'app_delete')]
    public function delete(EtuadiantRepository $er,int $id,EntityManagerInterface $entityManager):Response{
        $etu=$er->find($id);
        $entityManager->remove($etu);
         $entityManager->flush();
        return $this->redirectToRoute('app_affiche');
    
    }
     #[Route('/Update/{id}',name:'etu_update')]
        public function update(ManagerRegistry $doctrine,Request $request,$id,EtuadiantRepository $etu):response
        {
            $etud=$etu->find($id);
            $form=$this->createForm(EtuType::class,$etud);
            $form->handleRequest($request); 
           if ($form->isSubmitted() ) 
           {
            $em=$doctrine->getManager(); 
            $em->flush();
            return $this->redirectToRoute('app_affiche');
        }
        return $this->render('etuadiant/update.html.twig',['form'=>$form->createView()]) ;
        
        }
    }
  

