<?php 

namespace So\Blog\Controller;

use Config;
use So\Blog\Class\Controller;
use So\Blog\Class\Mail;
use So\Blog\HTML\FormValidatorHtml;

class HomeController extends Controller
{
    /**
     * Show index blog page
     */
    public function index(): string
    {
        $model = $this->getModel('articles');
        $data = $model->read(3);
        
        return $this->render('home/index.html.twig', ['items' => $data]);
    }

    /**
     * Contact form processing
     */
    public function contact()
    {
        $model = $this->getModel('articles');
        $data = $model->read(3);
        $config = new Config;

        if (empty($_POST['token']))
        {
            return $this->redirect(BASEURL);
        }

        if (!$this->csrf->verif($_POST['token']))
        {
            return $this->redirect(BASEURL);
        }

        $validator = new FormValidatorHtml($_POST);
        $formData = $validator->validate();
        if (empty($formData['name']) || empty($formData['email']) || empty($formData['subject']) || empty($formData['message']))
        {
            return $this->render('home/index.html.twig', ['items' => $data, 'error' => ['contact' => 'Veuillez vérifier toutes les informations du formulaire !']]);
        }

        $tmpl = file_get_contents(__DIR__ . '/../../templates/contact.html');
        $body = str_replace('{name}', $formData['name'], $tmpl);
        $body = str_replace('{email}', $formData['email'], $body);
        $body = str_replace('{subject}', $formData['subject'], $body);
        $body = str_replace('{message}', $formData['message'], $body);
        
        $mail = new Mail('Contact depuis le site internet - ' . $formData['subject'], ['name' => $config->name, 'email' => $config->from], $body, true);
        if ($mail->send())
        {
            return $this->render('home/index.html.twig', ['items' => $data, 'success' => ['contact' => 'Votre e-mail a bien été envoyé. Nous le traiterons dans les plus brefs délais.']]);
        }

        return $this->render('home/index.html.twig', ['items' => $data, 'error' => ['contact' => 'Une erreur est survenue lors de l\'envoi du mail.']]);
    }
    
}
