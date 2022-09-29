<?php

/**
 * PHP Version 7.4.3
 *
 * @author Olof Johansson <oljo@protonmail.ch>
 */

namespace Xolof;


/**
 * A class for registering paths.
 *
 * Paths can be registered with methods GET or POST.
 */
class Router
{
    protected string $request;
    protected string $requestMethod;
    protected string $requireFile;
    protected string $redirectPath;
    protected Post   $post;

    /**
     * This is the constructor.
     *
     * @param string $request       The incoming requested path.
     * @param string $requestMethod The $_SERVER["REQUEST_METHOD"].
     * @param Post   $post          A class handling posts.
     */
    public function __construct(string $request, string $requestMethod, Post $post)
    {
        $this->request = $request;
        $this->requestMethod = $requestMethod;
        $this->post = $post;
    }

    /**
     * If the requested path and method matches,
     * set a file to require.
     *
     * @param string $path The requested path.
     * @param string $file The filesystem path to the file to inlude.
     *
     * @return void
     */
    public function get($path, $file): void
    {
        if ($this->requestMethod === "GET"
            && $this->request === $path
        ) {
            $this->requireFile = $file;
        };
    }

    /**
     * If the requested path and method matches,
     * set a file to require.
     *
     * @param string $path The requested path.
     * @param string $file The filesystem path to the file to inlude.
     *
     * @return void
     */
    public function post($path, $file): void
    {
        if ($this->requestMethod === "POST"
            && $this->request === $path
        ) {
            $this->requireFile = $file;
        };
    }

    /**
     * Set redirectPath to /404 if the number of slashes in the request are too many.
     *
     * @return void
     */
    public function validate(): void
    {
        $exploded = explode("/", $this->request);

        if (array_key_exists(3, $exploded)) {
            $this->redirectPath = "/404";
        }
    }

    /**
     * Check if the path corresponds to a posts slug.
     * If there's a match, set the file to require
     * and return the posts JSON.
     *
     * @return ?object
     */
    public function getSpecialPage(): ?object 
    {
        if ($this->requestMethod === "GET") {
            $exploded = explode("/", $this->request);

            if (array_key_exists(1, $exploded)) {
                $slug = $exploded[1];
                $post = $this->post;
                $post->findPostBySlug($slug);
                $postJson = $post->getJson();
            }

            if (isset($postJson)) {
                if ($postJson !== false) {
                    $this->requireFile = "../view/post.php";
                    return $postJson;
                }
            }
        }

        return null;
    }

    /**
     * Return the file to require.
     *
     * @return ?string
     */
    public function getRequireFile(): ?string 
    {
        return $this->requireFile ?? null;
    }

    /**
     * Return the path to redirect to.
     *
     * @return ?string
     */
    public function getRedirectPath(): ?string
    {
        return $this->redirectPath ?? null;
    }
}
