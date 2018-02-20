<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use AppBundle\Entity\User;
use AppBundle\Form\ImageType;
use AppBundle\Form\RegisterType;
use AppBundle\Service\ImageServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ImageController extends Controller
{
    /**
     * @var ImageServiceInterface
     */
    private $imageService;

    /**
     * ImageController constructor.
     * @param ImageServiceInterface $imageService
     */
    public function __construct(ImageServiceInterface $imageService)
    {
        $this->imageService = $imageService;
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/upload" , name="image_upload")
     */
    public function uploadAction(Request $request)
    {

        $image = new Image();

        $form = $this->createForm(ImageType::class,$image);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $parameter = $this->getParameter('images_directory');
            $id = $this->getUser()->getId();

            $this->imageService->upload($image,$parameter,$id);

            return $this->redirectToRoute('image_upload');
        }

        return $this->render('test.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/images", name="all_images")
     */
    public function allImages(){

        $allImages = $this->imageService->allImages();

        return $this->render('images.html.twig',['allImages' => $allImages]);

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/userImages", name="users_images")
     */
    public function userImages(){

        $id = $this->getUser()->getId();

        $userImages = $this->imageService->userImages($id);

        return $this->render('userImages.html.twig',['allImages' => $userImages]);
    }

}
