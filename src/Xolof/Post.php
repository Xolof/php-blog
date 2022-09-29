<?php

/**
 * PHP Version 7.4.3
 *
 * @author Olof Johansson <oljo@protonmail.ch>
 */

namespace Xolof;

use \Parsedown;
use \stdClass;
use \DateTime;


/**
 * A class for managing posts data.
 *
 * This class performs some different operations related to posts.
 * It will later be broken out into separate classes.
 */
class Post
{
    protected $id;
    protected $json;
    protected string $postsFile;

    /**
     * This is the constructor.
     *
     * @param string $postsFile The file containing the posts data.
     */
    public function __construct($postsFile)
    {
        $this->id = null;
        $this->json = null;
        $this->postsFile = $postsFile;
    }

    /**
     * Get an array with all the posts data.
     *
     * @return array<object>
     */
    public function getAllPosts(): array
    {
        if (file_exists($this->postsFile)) {
            $handle = fopen($this->postsFile, "rb");
            $fileSize = filesize($this->postsFile);
            if ($handle && $fileSize > 0) {
                $posts = fread($handle, $fileSize);
                fclose($handle);
                if ($posts) {
                    return (array) json_decode($posts);
                }
            }
        }
        return [];
    }

    /**
     * Find a post by it's slug.
     *
     * @param string $slug A slug used to identify the post.
     *
     * @return void
     */
    public function findPostBySlug($slug): void
    {
        $allPosts = $this->getAllPosts();

        foreach ($allPosts as $p) {
            if (!isset($p->slug)) {
                continue;
            }
            if ($p->slug === $slug) {
                $post = $p;
                if (isset($post->id)) {
                    $this->id = $post->id;
                }
                $this->json = $post;
            }
        }
    }

    /**
     * Get the posts data as JSON.
     *
     * @return ?object
     */
    public function getJson(): ?object
    {
        if ($this->json) {
            return $this->json;
        }
        return null;
    }

    /**
     * Gets the ingress of a post.
     *
     * @param string $content The whole main content of the post.
     *
     * @return string $res The ingress of the post.
     */
    public function getIngress($content): string
    {
        $Parsedown = new Parsedown();

        $content = $Parsedown->text($content);

        $res = "";

        $split = str_split($content);

        $openHtmlTags = 0;
        $openMarkDownTags = 0;

        foreach ($split as $char) {
            if ($char === "<") {
                $openHtmlTags += 1;
            }

            if ($char === ">" ) {
                $openHtmlTags -= 1;
            }

            $res .= $char;

            if (strlen($res) >= 500 && $openHtmlTags === 0) {
                if ($char === ".") {
                    break;
                }

                if (strlen($res) >= 800 && $openHtmlTags === 0) {
                    break;
                }
            }
        }

        return $res;
    }

    /**
     * Get a post by it's id.
     *
     * @param int $id The post's id.
     *
     * @return ?object
     */
    public function getPost($id): ?object
    {
        $allPosts = $this->getAllPosts();
        foreach ($allPosts as $post) {
            if (!isset($post->id)) {
                continue;
            }
            if (intval($post->id) === intval($id)) {
                return $post;
            }
        }
        return null;
    }

    /**
     * Get a slug from a post's title.
     *
     * @param string $title The title to slugify.
     *
     * @return string
     */
    public function slugify($title): string
    {
        return strtolower(str_replace(" ", "-", $title));
    }

    /**
     * Check if the title already exists.
     *
     * @param array<object> $posts  An array containing all the posts.
     * @param string        $title  The title of the post to create or update.
     * @param int           $postId The id of the post to create or update.
     *
     * @return bool
     */
    public function titleAlreadyExists($posts, $title, $postId): bool
    {
        foreach ($posts as $post) {
            if (!isset($post->title) || !isset($post->id)) {
                continue;
            }
            if ($post->title === $title && $post->id != $postId) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get all of the tags.
     *
     * @return array<string> $tags An array with all tags.
     */
    public function getAllTags(): array
    {
        $posts = $this->getAllPosts();
        $tags = [];
        foreach ($posts as $post) {
            if (!isset($post->metadata)) {
                continue;
            }
            $postTags = explode(" ", $post->metadata->tags);
            foreach ($postTags as $tag) {
                if (!in_array($tag, $tags)) {
                    $tags[] = $tag;
                }
            }
        }
        return $tags;
    }

    /**
     * Add a comment for a post.
     *
     * @param int    $postId  The id of the post to add a comment to.
     * @param string $name    The name the user entered in the comment form.
     * @param string $comment The comment.
     *
     * @return bool
     */
    public function addComment($postId, $name, $comment): bool
    {
        $posts = $this->getAllPosts();

        $commentObj = new stdClass();

        $currentDate = new DateTime();
        $dateStr = $currentDate->format("Y-m-d H:i");
        $commentObj->date = $dateStr;

        $commentObj->comment = $comment;
        $commentObj->name = $name;

        foreach ($posts as $post) {
            if (!isset($post->comments) || !isset($post->id)) {
                continue;
            }
            if ($post->id == $postId) {
                $post->comments[] = $commentObj;
            }
        }

        try {
            $this->savePosts($posts);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Save the posts data.
     *
     * @param array<object> $posts An array containing all the posts.
     *
     * @return void
     */
    public function savePosts($posts): void
    {
        if (file_exists($this->postsFile)) {
            $handle = fopen($this->postsFile, "wb");
            $fileSize = filesize($this->postsFile);
            if ($handle && $fileSize > 0) {
                $postsJson = json_encode($posts);
                if ($postsJson) {
                    fwrite($handle, $postsJson);
                }
                fclose($handle);
            }
        }
    }
}
