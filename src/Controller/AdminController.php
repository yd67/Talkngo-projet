<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Country;
use App\Form\CountryFormType;
use App\Form\UpdateAdminFormType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Repository\CountryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index2(): Response
    { $repo = $this->getDoctrine()->getRepository(Country::class);
        $countrys = $repo->findAll();

        return $this->render('admin/admin.html.twig', [
            'controller_name' => 'AdminController',
            'countrys' => $countrys
    
        ]);
    }
    /**
     * @Route("/admin/add", name="add_country")
     */
    public function indexPays(CountryRepository $countryRepository): Response
    { 
      
        $repo = $this->getDoctrine()->getRepository(Country::class);
        $countrys = $repo->findAll();
        $country = $countryRepository->findAll();
        
        return $this->render('admin/addCountry.html.twig', [
            'country' => $country,
            'countrys' => $countrys
        ]);
    }



    /**
     * @Route("/admin/add/update-{id}", name="country_update")
     */
    public function updatePays(CountryRepository $countryRepository, $id, Request $request): Response
    { 
        
        $country = $countryRepository->find($id);
        $form = $this->createForm(CountryFormType::class, $country);
        $form->handleRequest($request);
        $repo = $this->getDoctrine()->getRepository(Country::class);
        $countrys = $repo->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $oldNomImg = $country->getImagePays(); //ancien image
            $oldCheminImg = $this->getParameter('dossier_photos_pays') . '/' . $oldNomImg;

            

            $infoImg = $form['imagePays']->getData();
            $extensionImg = $infoImg->guessExtension();

            $nomImg = time() . '.' . $extensionImg;
            $infoImg->move($this->getParameter('dossier_photos_pays'), $nomImg);
            $country->setImagePays($nomImg);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($country);
            $manager->flush();

            $this->addFlash(
                'success',
                'La page pays a bien été modifiée'
            );

           
       
        return $this->redirectToRoute('add_country');
        }
        return $this->render('admin/updateCountry.html.twig', [
            'countryForm' => $form->createView(),
            'countrys' => $countrys,
            'country'  => $country
        ]);
    }





     /**
     * @Route("/admin/user", name="admin_user")
     */
    public function index(UserRepository $userRepository): Response
    {
        $repo = $this->getDoctrine()->getRepository(Country::class);
        $countrys = $repo->findAll();
        $users = $userRepository->findAll();
        $utilisateurs = [];
        for ($i=0; $i<count($users); $i++) {
            $datetime = date_format($users[$i]->getAge(), 'Y-m-d');
            $timestamp = strtotime($datetime);
            $utilisateurs['age'][$i] = abs((time() - $timestamp) / (3600 * 24 * 365));
        }
        $i = $i-1;
        return $this->render('admin/user.html.twig', [
            'users' => $users,
            'user_age' => $utilisateurs,
            'user_count' => $i,
            'countrys' => $countrys
        ]);
    }

     /**
     * @Route("/admin/user/create", name="create_user")
     */
    public function createUser(Request $request,UserPasswordEncoderInterface $passwordEncoder){

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $repo = $this->getDoctrine()->getRepository(Country::class);
        $countrys = $repo->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            //gestion des images 
            $infoImg = $form['img']->getData(); // récupère les infos de l'image 
            $extensionImg = $infoImg->guessExtension(); // récupère le format de l'image 
            $nomImg = time() . '.' . $extensionImg; // compose un nom d'image unique
            $infoImg->move($this->getParameter('dossier_photos_user'), $nomImg); // déplace l'image
            $user->setImg($nomImg);
           

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
           
            return $this->redirectToRoute('admin_user');
        }

        return $this->render('admin/userCreate.html.twig', [
            'registrationForm' => $form->createView(),
            'countrys' =>$countrys
        ]);
    }
        

    /**
     * @Route("/admin/user/update-{id}", name="user_update") 
     */
    public function updateUser(UserRepository $userRepository, $id, Request $request)
    {
        $user = $userRepository->find($id);
        $form = $this->createForm(UpdateAdminFormType::class, $user);
        $form->handleRequest($request);
        $repo = $this->getDoctrine()->getRepository(Country::class);
        $countrys = $repo->findAll();



        if ($form->isSubmitted() && $form->isValid()) {

                if ($form->get('img')->getData() !== null){

                    $oldNomImg = $user->getImg(); //ancien image
                    $oldCheminImg = $this->getParameter('dossier_photos_user') . '/' . $oldNomImg;
                    if (file_exists($oldCheminImg)) {
                        unlink($oldCheminImg);
                    }
                    $infoImg = $form['img']->getData();
                    $extensionImg = $infoImg->guessExtension();
                    $nomImg = time() . '.' . $extensionImg;
                    $infoImg->move($this->getParameter('dossier_photos_user'), $nomImg);
                    $user->setImg($nomImg);
                }

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'L\'utilisateur  a bien été modifiée'
            );

            return $this->redirectToRoute('admin_user');
        }
        return $this->render('admin/updateUser.html.twig', [
            'updateAdminForm' => $form->createView(),
            'countrys' => $countrys
        ]);
    }

     /**
     * @Route("/admin/user/delete-{id}", name="user_delete") 
     */

     public function deleteUser(UserRepository $userRepository,$id){

        $user = $userRepository->find($id);
        $oldNomImg = $user->getImg();
        $oldCheminImg = $this->getParameter('dossier_photos_user') . '/' . $oldNomImg;

        if (file_exists($oldCheminImg)) {
            unlink($oldCheminImg);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($user);
        $manager->flush();
        $this->addFlash(
            'success',
            'L\'utilisateur a bien été supprimée'
        );
        return $this->redirectToRoute('admin_user');
    }
}
