<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;
use Psr\Log\LoggerInterface;

use App\Form\UserFormType;



class UserController extends AbstractController
{

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    /**
     * @Route("/user", name="user_create", methods={"POST"})
     */
    public function createUser(Request $request): Response
    {
       
        $form = $this->createForm(UserFormType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() ) {
            $this->logger->info('data:'.$data );
            $data = $form->getData();

            
            $firstName = $data['firstName'];
            $lastName = $data['lastName'];
            $dateOfBirth = $data['dateOfBirth']->format('Y-m-d');
            $age = $data['age'];
            $imageUrl = $data['imageUrl'];

            $folderName = $firstName . '_' . $lastName;
            $filesystem = new Filesystem();
            $filesystem->mkdir($folderName);
            $this->logger->info('folderName:'.$folderName );

            $imageContent = file_get_contents($imageUrl);
            $imagePath = $folderName . '/' . $dateOfBirth . '.jpg';
            file_put_contents($imagePath, $imageContent);

            $this->logger->info('imagePath:'.$imagePath );
         
            $this->logger->info('Form submitted successfully!' );
            return new Response('Form submitted successfully!');
        }

        return $this->render('user/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
