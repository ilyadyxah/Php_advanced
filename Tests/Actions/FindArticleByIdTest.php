<?php

namespace Tests\Actions;

use App\Entities\Article\Article;
use App\Entities\EntityInterface;
use App\Exceptions\UserNotFoundException;
use App\Http\Actions\Article\FindArticleById;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\SuccessfulResponse;
use App\Repositories\ArticleRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Tests\Traits\LoggerTrait;

class FindArticleByIdTest extends TestCase
{
    use LoggerTrait;
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testItReturnsErrorResponseIfNoIdProvided(): void
    {
        $request = new Request([], [], '');
        $articleRepository = $this->getArticleRepository([]);

        $action = new FindArticleById($articleRepository, $this->getLogger());
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
    public function testItReturnsErrorResponseIfArticleNotFound(): void
    {
        $request = new Request(['id' => '125'], [], '');

        $articleRepository = $this->getArticleRepository([]);
        $action = new FindArticleById($articleRepository, $this->getLogger());

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
        $request = new Request(['id' => '1'], [], '');

        $articleRepository = $this->getArticleRepository([
            new Article(
                '1',
                'title',
                'text'
            ),
            new Article(
                '1',
                'title',
                'text'
            ),
        ]);

        $action = new FindArticleById($articleRepository, $this->getLogger());
        $response = $action->handle($request);

        $this->assertInstanceOf(SuccessfulResponse::class, $response);
        $this->expectOutputString('{"success":true,"data":{"authorId":"1","title":"title","text":"text"}}');

        $response->send();
    }

    private function getArticleRepository(array $articles): ArticleRepositoryInterface
    {
        return new class($articles) implements ArticleRepositoryInterface {

            public function __construct(
                private array $articles
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
                foreach ($this->articles as $article) {
                    if ($article instanceof Article && $id === $article->getId()) {
                        return $article;
                    }
                }

                throw new UserNotFoundException("Not found");
            }
        };
    }
}
