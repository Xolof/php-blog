<?php

declare(strict_types=1);

namespace Xolof;

use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase
{
    public function testSuccessfulGetRequest(): void
    {
        $postObj = new Post(dirname(__DIR__) . "/tests/assets/posts.json");

        $router = new Router("/", "GET", $postObj);

        $router->get("/", "../view/start.php");

        $this->assertEquals(
            "../view/start.php",
            $router->getRequireFile()
        );

        $this->assertEquals(
            false,
            $router->getRedirectPath()
        );
    }

    public function testFailedGetRequest(): void
    {
        $postObj = new Post(dirname(__DIR__) . "/tests/assets/posts.json");

        $router = new Router("/mumin", "GET", $postObj);

        $router->get("/", "../view/start.php");

        $this->assertEquals(
            false,
            $router->getRequireFile()
        );
    }

    public function testInvalidGetRequest(): void
    {
        $postObj = new Post(dirname(__DIR__) . "/tests/assets/posts.json");

        $router = new Router("/", "POST", $postObj);

        $router->get("/", "../view/start.php");

        $this->assertEquals(
            false,
            $router->getRequireFile()
        );
    }

    public function testSuccessfulPostRequest(): void
    {
        $postObj = new Post(dirname(__DIR__) . "/tests/assets/posts.json");

        $router = new Router("/", "POST", $postObj);

        $router->post("/", "../process.php");

        $this->assertEquals(
            "../process.php",
            $router->getRequireFile()
        );

        $this->assertEquals(
            false,
            $router->getRedirectPath()
        );
    }

    public function testFailedPostRequest(): void
    {
        $postObj = new Post(dirname(__DIR__) . "/tests/assets/posts.json");

        $router = new Router("/nothing-here", "POST", $postObj);

        $router->post("/", "../process.php");

        $this->assertEquals(
            false,
            $router->getRequireFile()
        );
    }

    public function testInvalidPostRequest(): void
    {
        $postObj = new Post(dirname(__DIR__) . "/tests/assets/posts.json");

        $router = new Router("/", "GET", $postObj);

        $router->post("/", "../view/start.php");

        $this->assertEquals(
            false,
            $router->getRequireFile()
        );
    }

    public function testSetRedirectPath(): void
    {
        $postObj = new Post(dirname(__DIR__) . "/tests/assets/posts.json");

        $router = new Router("/", "GET", $postObj);

        $router->validate();

        $this->assertEquals(
            false,
            $router->getRedirectPath()
        );

        $router = new Router("/apa/ko/gris/får", "GET", $postObj);

        $router->validate();

        $this->assertEquals(
            "/404",
            $router->getRedirectPath()
        );
    }

    public function testGetSpecialPage(): void
    {
        $postObj = new Post(dirname(__DIR__) . "/tests/assets/posts.json");

        $router = new Router("/perch", "GET", $postObj);

        $router->getSpecialPage();

        $this->assertEquals(
            "../view/post.php",
            $router->getRequireFile()
        );
    }
}
