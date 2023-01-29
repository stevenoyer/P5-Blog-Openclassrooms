<?php 

namespace So\Blog\HTML;

class FormValidatorHtml
{
    private array $post;

    /**
     * Class constructor.
     */
    public function __construct(array $post = null)
    {
        $this->post = $post;
    }

    public function validate(): array
    {
        $post = [];
        foreach ($this->post as $key => $data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            $post[$key] = $data;
        }

        return $post;
    }
}