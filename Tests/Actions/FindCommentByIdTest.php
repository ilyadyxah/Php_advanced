<?php

namespace Tests\Actions;

use App\Entities\Comment\Comment;
use App\Entities\EntityInterface;
use App\Exceptions\UserNotFoundException;
use App\Http\Actions\Comment\FindCommentById;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\SuccessfulResponse;
use App\Repositories\CommentRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Tests\Traits\LoggerTrait;

class FindCommentByIdTest extends TestCase
{
    use LoggerTrait;
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testItReturnsErrorResponseIfNoIdProvided(): void
    {
        $request = new Request([], [], '');
        $commentRepository = $this->getCommentRepository([]);

        $action = new FindCommentById($commentRepository, $this->getLogger());
        $response = $action->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);

        $this->expectOutputString(
            '{"success":false,"reason":"No such query param in the request: id"}'
        );

        $response->send();
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testItReturnsErrorResponseIfCommentNotFound(): void
    {
        $request = new Request(['id' => '14'], [], '');

        $commentRepository = $this->getCommentrepository([]);
        $action = new FindCommentById($commentRepository, $this->getLogger());

        $response = $action->handle($request);
        $this->assertInstanceOf(ErrorResponse::class, $response);

        $this->expectOutputString('{"success":false,"reason":"Not found"}');
        $response->send();
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testItReturnsSuccessfulResponse(): void
    {
        $request = new Request(['id' => 0], [], '');

        $commentRepository = $this->getCommentRepository([
            new Comment(
                '1',
                '1',
                'text'
            ),
        ]);

        $action = new FindCommentById($commentRepository, $this->getLogger());
        $response = $action->handle($request);

        $this->assertInstanceOf(SuccessfulResponse::class, $response);
        $this->expectOutputString('{"success":true,"data":{"authorId":"1","title":"title","text":"text"}}');

        $response->send();
    }

    private function getCommentRepository(array $comments): CommentRepositoryInterface
    {
        return new class($comments) implements CommentRepositoryInterface {

            public function __construct(
                private array $comments
            )
            {
            }

            public function getId(EntityInterface $entity)
            {
            }

            public function save(EntityInterface $entity)
            {
            }

            public function get(int $id): EntityInterface
            {
                foreach ($this->comments as $comment) {
                    if ($comment instanceof Comment && $id === $comment->getId()) {
                        return $comment;
                    }
                }

                throw new UserNotFoundException("Not found");
            }
        };
    }
}
