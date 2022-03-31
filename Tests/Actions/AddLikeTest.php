<?php

namespace Tests\Actions;

use App\Entities\EntityInterface;
use App\Http\Actions\Like\AddLike;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\SuccessfulResponse;
use App\Repositories\LikeRepositoryInterface;
use PHPUnit\Framework\TestCase;

class AddLikeTest extends TestCase
{

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testItReturnsErrorResponseIfLikeNotProvided(): void
    {
        $request = new Request([], [], '
            {
                "articleId": "2",
                "userId": "1"
            }
            ');

        $likeRepository = $this->getLikeRepository('');

        $action = new AddLike($likeRepository);
        $response = $action->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);

        $this->expectOutputString(
            '{"success":false,"reason":"No such field: like"}'
        );

        $response->send();
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testItReturnsErrorResponseIfLikeExists(): void
    {
        $request = $this->getRequest();
        $likeRepository = $this->getLikeRepository('Like');

        $action = new AddLike($likeRepository);
        $response = $action->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);

        $this->expectOutputString(
            '{"success":false,"reason":"Like already exists"}'
        );

        $response->send();
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testItReturnsErrorResponseIfArticleNotExists(): void
    {
        $request = $this->getRequest();
        $likeRepository = $this->getLikeRepository('Article');

        $action = new AddLike($likeRepository);
        $response = $action->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);

        $this->expectOutputString(
            '{"success":false,"reason":"Article not exists"}'
        );

        $response->send();
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testItReturnsErrorResponseIfUserNotExists(): void
    {
        $request = $this->getRequest();
        $likeRepository = $this->getLikeRepository('User');

        $action = new AddLike($likeRepository);
        $response = $action->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);

        $this->expectOutputString(
            '{"success":false,"reason":"User not exists"}'
        );

        $response->send();
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testItReturnsSuccessfulResponse(): void
    {
        $request = $this->getRequest();
        $likeRepository = $this->getLikeRepository('');

        $action = new AddLike($likeRepository);
        $response = $action->handle($request);

        $this->assertInstanceOf(SuccessfulResponse::class, $response);
        $this->expectOutputString('{"success":true,"data":{"articleId":1,"userId":1}}');

        $response->send();
    }

    private function getLikeRepository($exception): LikeRepositoryInterface
    {
        return new class($exception) implements LikeRepositoryInterface {

            private $exception;

            public function __construct($exception)
            {
                $this->exception = $exception;
            }

            public function save(EntityInterface $entity)
            {
                // TODO: Implement get() method.
            }

            public function getLikeId($entity): ?int
            {
                return $this->exception === 'Like';
            }

            public function isArticle($entity): bool
            {
                return !($this->exception === 'Article');
            }

            public function isUser($entity): bool
            {
                return !($this->exception === 'User');
            }

            public function get(int $id): EntityInterface
            {
                // TODO: Implement get() method.
            }
        };
    }

    public function getRequest(): Request
    {
        return new Request([], [], '
            {
                "articleId": "1",
                "userId": "1",
                "like": "true"
            }
        ');
    }
}