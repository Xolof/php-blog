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
        $markdown = substr($content, 0, 500);
        $exploded = explode(".", $markdown);
        $lastItem = $exploded[array_key_last($exploded)];

        if (!(preg_match('/.*\)$/', $lastItem) || preg_match('/.*\/a>.*/', $lastItem))) {
            $trimmed = array_slice($exploded, 0, count($exploded) -1);
            $markdown = implode(".", $trimmed) . ".";
        }

        return $markdown;
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
        $posts = getAllPosts();

        $commentObj = new \stdClass();

        $currentDate = new DateTime();
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
            savePosts($posts);
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
