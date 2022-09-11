<?php

namespace Xolof;

class Post
{
    protected $id = false;
    protected $json = false;
    protected $postsFile;

    public function __construct($postsFile)
    {
        $this->postsFile = $postsFile;
    }

    public function getAllPosts()
    {
        if (file_exists($this->postsFile)) {
            $handle = fopen($this->postsFile, "rb");
            $posts = fread($handle, filesize($this->postsFile));
            fclose($handle);
            return (array) json_decode($posts);
        }
        return [];
    }

    public function findPostBySlug($slug)
    {
        $allPosts = $this->getAllPosts();

        foreach ($allPosts as $p) {
            if ($p->slug === $slug) {
                $post = $p;
                $this->id = $post->id;
                $this->json = $post;
            }
        }
    }

    public function getJson()
    {
        return $this->json;
    }

    public function getIngress($content)
    {
        $Parsedown = new \Parsedown();

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

            if (strlen($res) >= 500 && $openHtmlTags < 1) {
                if ($char === ".") {
                    break;
                }

                if (strlen($res) >= 800 && $openHtmlTags < 1) {
                    break;
                }
            }
        }

        return $res;
    }

    public function getPost($id)
    {
        $allPosts = $this->getAllPosts();
        foreach ($allPosts as $post) {
            if (intval($post->id) === intval($id)) {
                return $post;
            }
        }
        return false;
    }

    public function slugify($title)
    {
        return strtolower(str_replace(" ", "-", $title));
    }

    public function titleAlreadyExists($posts, $title, $postId)
    {
        foreach ($posts as $post) {
            if ($post->title === $title && $post->id != $postId) {
                return true;
            }
        }
        return false;
    }

    public function getAllTags()
    {
        $posts = $this->getAllPosts();
        $tags = [];
        foreach ($posts as $post) {
            $postTags = explode(" ", $post->metadata->tags);
            foreach ($postTags as $tag) {
                if (!in_array($tag, $tags)) {
                    $tags[] = $tag;
                }
            }
        }
        return $tags;
    }

    public function addComment($postId, $name, $comment)
    {
        $posts = $this->getAllPosts();

        $commentObj = new \stdClass();

        $currentDate = new \DateTime();
        $dateStr = $currentDate->format("Y-m-d H:i");
        $commentObj->date = $dateStr;

        $commentObj->comment = $comment;
        $commentObj->name = $name;

        foreach ($posts as $post) {
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

    public function savePosts($posts)
    {
        $handle = fopen($this->postsFile, "wb");
        fwrite($handle, json_encode($posts));
        fclose($handle);
    }
}
