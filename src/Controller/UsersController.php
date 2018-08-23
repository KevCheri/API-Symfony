<?php

namespace App\Controller;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;


class UsersController extends FOSRestController
{
    private $userRepository;
    private $em;
    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)   {
        $this->userRepository = $userRepository;
        $this->em = $em;
    }
    public function getUsersAction()
    {
        $users = $this->userRepository->findAll();
        return $this->view($users);
    }
    // "get_users"            [GET] /users

    /**
     * @param User $user
     * @return \FOS\RestBundle\View\View
     */
    public function getUserAction(User $user)
    {
        return $this->view($user);
    }
    // "get_user"             [GET] /users/{id}

    /**
     * @Rest\Post("/users")
     * @ParamConverter("user", converter="fos_rest.request_body")
     * @param User $user
     * @param ConstraintViolationListInterface $validationErrors
     * @return \FOS\RestBundle\View\View
     */
    public function postUsersAction(User $user, ConstraintViolationListInterface $validationErrors) {
        if($validationErrors->count() > 0 ){
            /** @var ConstraintViolation $constraintViolation */
            $error = [];
            foreach ($validationErrors as $constraintViolation){
                $message = $constraintViolation-> getMessage(); // Returns the violation message. (Ex: This value should not be blank).
                $propertyPath = $constraintViolation-> getPropertyPath(); // Returns the property path from the root element to the violation. (Ex: Lastname).
                $error[] = $message . ' ' . $propertyPath;
                // Handle validation errors
            }
            return $this->view($error);
        }
        $this->em->persist($user);
        $this->em->flush();
        return $this->view($user);
    }
    // "post_users"           [POST] /users
    public function putUserAction(Request $request, int $id, ValidatorInterface $validator){
        $user= new User;
        $error = [];
        /** @var ConstraintViolationList $validationErrors */
        $validationErrors = $validator->validate($user);
        foreach ($validationErrors as $constraintViolation){
            $message = $constraintViolation-> getMessage (); // Returns the violation message. (Ex: This value should not be blank).
            $propertyPath = $constraintViolation-> getPropertyPath(); // Returns the property path from the root element to the violation. (Ex: Lastname).
            $error[] = $message . ' ' . $propertyPath;
        }
        $sf = $request->get('Firstname');
        $sl = $request->get('Lastname');
        $se = $request->get('Email');
        $user = $this->userRepository->find($id);
        If($sf) {
            $user->setFirstname($sf);
        }
        If($sl) {
            $user->setLastname($sl);
        }
        If($se){
            $user->setEmail($se);
        }
        $this->em->persist($user);
        $this->em->flush();
        return $this->view($user);
        // $request->get('firstname')
    }
    // "put_user"             [PUT] /users/{id}
    public function deleteUserAction(User $user) {
        $this->em->remove($user);
        $this->em->flush();
    }
    // "delete_user"          [DELETE] /users/{id}}
}