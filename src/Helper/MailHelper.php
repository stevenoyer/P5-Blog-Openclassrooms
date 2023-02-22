<?php 

namespace So\Blog\Helper;

use So\Blog\Class\Mail;

class MailHelper
{
    private string $body;

    /**
     * This function will create the validation url
     */
    public function getUrlConfirmation(string $token): string|bool
    {
        if (empty($token)) return false;
        return BASEURL . '/confirm/' . $token;
    }

    /**
     * This function get and replace variables in body mail
     */
    public function getBody(array $infos, string $tmpl): string
    {
        $this->body = str_replace('{email}', $infos['email'], $tmpl);
        $this->body = str_replace('{name}', $infos['name'], $this->body);
        $this->body = str_replace('{password}', $infos['password'], $this->body);
        $this->body = str_replace('{blog_url}', BASEURL, $this->body);
        return $this->body = str_replace('{link}', $this->getUrlConfirmation($infos['token_validation']), $this->body);
    }

    /**
     * Function that sends a confirmation email to the user
     */
    public function sendMailConfirmation(array $infos, bool $register_admin = false): bool
    {
        if (empty($infos)) return false;
        if (empty($infos['email'])) return false;
        if (empty($infos['name'])) return false;
        if (empty($infos['token_validation'])) return false;

        $file = file_get_contents(__DIR__ . '/../../templates/register.html');
        if ($register_admin)
        {
            $file = file_get_contents(__DIR__ . '/../../templates/admin_register.html');
        }
         
        $mail = new Mail('Bienvenue sur notre nouveau blog ' . $infos['name'] . ' !', $infos, $this->getBody($infos, $file), true);

        if ($mail->send())
        {
            return true;
        }

        return false;
    }

}
