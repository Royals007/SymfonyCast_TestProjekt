<?php


namespace AppBundle\Security;

use AppBundle\Form\LoginForm;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;


class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{

    //Create a Auth.Form -- USE of dependency Injection to inject the form.factory service
    //To add this Users data in DB used the EntityManager as the second argument

    private $formFactory;
    private $em;
    private $router;
    private $passwordEncoder;

    public function __construct(FormFactoryInterface $formFactory, EntityManager $em, RouterInterface $router, UserPasswordEncoder $passwordEncoder)
    {
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function getCredentials(Request $request)
    {
        // check the URL is /login and HTTP method is POST-- login form is in action state
        $isLoginSubmit = $request->getPathInfo() == '/login' && $request->isMethod('POST');

        if (!$isLoginSubmit) {
            return;
        }

        //Return the credentials
        $form = $this->formFactory->create(LoginForm::class);
        $form->handleRequest($request);

        // Not checking the validation form data
        // getting the login_data
        $loginData = $form->getData();


        $request->getSession()->set(
            Security::LAST_USERNAME,
            $loginData['_username']
        );

        return $loginData;

    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // getCredentials is not null, then authentication pass then symfony calls this method
        $username = $credentials['_username']; // this project it is an email

        //returns 'null' --Guard Auth. will and pass an error to USER
        //not return to USER object then check the checkCredentials calls by the GUARD
        return $this->em->getRepository('AppBundle:User')
            ->findOneBy(['email' => $username]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $password = $credentials['_password'];
        if ($this->passwordEncoder->isPasswordValid($user, $password)) {
            return true;
        }

        return false;
    }


    protected function getLoginUrl()
    {
        //Authentication fails -- then need to fill login details into loginUrl
        return $this->router->generate('security_login');
    }

//    protected function getDefaultSuccessRedirectUrl()
//    {
//        return $this->router->generate('homepage');
//    }


    //how to get the redirect the same page if information is given wrong.
//    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
//    {
//        return new RedirectResponse($this->router->generate('homepage'));
//    }

}