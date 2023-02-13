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

    /**
     * Process validate data
     */
    public function validate(): array
    {
        $post = [];
        foreach ($this->post as $key => $data)
        {
            $data = trim($data); // Delete inutile spaces
            $data = stripslashes($data); // Remove \ from string
            $data = strip_tags($data); // Remove tag HTML & PHP
            $data = htmlspecialchars($data); // convert special characters to HTML
            $post[$key] = $data;
        }

        return $post;
    }

    /**
     * Check if data is not empty
     */
    public function checkEmpty(): bool
    {
        foreach ($this->post as $data)
        {
            if (empty($data))
            {
                return false;
            }

            return true;
        }
    }
    
}
