<?php

namespace Xolof;

class Router
{
    protected $request;
    protected $requestMethod;
    protected $requireFile = false;
    protected $redirectPath = false;
    protected $post;

    public function __construct($request, $requestMethod, $post)
    {
        $this->request = $request;
        $this->requestMethod = $requestMethod;
        $this->post = $post;
    }

    public function get($path, $file)
    {
        if ($this->requestMethod === "GET"
          && $this->request === $path) {
            $this->requireFile = $file;
        };
    }

    public function post($path, $file)
    {
        if ($this->requestMethod === "POST"
          && $this->request === $path) {
            $this->requireFile = $file;
        };
    }

    public function validate()
    {
        $exploded = explode("/", $this->request);

        if (array_key_exists(3, $exploded)) {
            $this->redirectPath = "/404";
        }
    }

    public function getSpecialPage()
    {
        if ($this->requestMethod === "GET") {
            $exploded = explode("/", $this->request);

            if (array_key_exists(1, $exploded)) {
                $slug = $exploded[1];
                $post = $this->post;
                $post->findPostBySlug($slug);
                $postJson = $post->getJson();
            }

            if ($postJson) {
                $this->requireFile = "../view/post.php";
                return $postJson;
            }
        }
    }

    public function getRequireFile()
    {
        return $this->requireFile;
    }

    public function getRedirectPath()
    {
        return $this->redirectPath;
    }
}
