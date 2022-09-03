<?php

declare(strict_types=1);

namespace Xolof;

use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase
{
    public function testSuccessfulGetRequest(): void
    {
        $router = new Router("/", "GET");

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
        $router = new Router("/mumin", "GET");

        $router->get("/", "../view/start.php");

        $this->assertEquals(
            false,
            $router->getRequireFile()
        );
    }

    public function testInvalidGetRequest(): void
    {
        $router = new Router("/", "POST");

        $router->get("/", "../view/start.php");

        $this->assertEquals(
            false,
            $router->getRequireFile()
        );
    }

    public function testSuccessfulPostRequest(): void
    {
        $router = new Router("/", "POST");

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
        $router = new Router("/nothing-here", "POST");

        $router->post("/", "../process.php");

        $this->assertEquals(
            false,
            $router->getRequireFile()
        );
    }

    public function testInvalidPostRequest(): void
    {
        $router = new Router("/", "GET");

        $router->post("/", "../view/start.php");

        $this->assertEquals(
            false,
            $router->getRequireFile()
        );
    }

    public function testSetRedirectPath(): void
    {
        $router = new Router("/", "GET");

        $router->validate();
        
        $this->assertEquals(
            false,
            $router->getRedirectPath()
        );

        $router = new Router("/apa/ko/gris/får", "GET");

        $router->validate();

        $this->assertEquals(
            "/404",
            $router->getRedirectPath()
        );
    }

    public function testGetSpecialPage(): void
    {
        /**
         * Detta måste mockas,
         * för tänk om inlägget "perch" inte finns.
         */
        $router = new Router("/perch", "GET");

        $router->getSpecialPage();

        $this->assertEquals(
            "../view/post.php",
            $router->getRequireFile()
        );
    }
}
