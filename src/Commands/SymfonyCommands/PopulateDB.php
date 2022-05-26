<?php

namespace App\Commands\SymfonyCommands;

use App\Commands\CreateArticleCommand;
use App\Commands\CreateCommentCommand;
use App\Commands\CreateUserCommand;
use App\Entities\Article\Article;
use App\Entities\Comment\Comment;
use App\Entities\User\User;
use App\Repositories\ArticleRepository;
use App\Repositories\UserRepository;
use Faker\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateDB extends Command
{
    public function __construct(
        private Generator            $faker,
        private UserRepository       $usersRepository,
        private ArticleRepository    $articlesRepository,
        private CreateUserCommand    $createUserCommand,
        private CreateArticleCommand $articleCommand,
        private CreateCommentCommand $commentCommand
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('fake-data:populate-db')
            ->setDescription('Populates DB with fake data')
            ->addOption('usersNumber', 'u', InputOption::VALUE_OPTIONAL, 'Users number')
            ->addOption('articlesNumber', 'a', InputOption::VALUE_OPTIONAL, 'Articles number')
            ->addOption('commentsNumber', 'c', InputOption::VALUE_OPTIONAL, 'Comments number');
    }

    protected function execute(
        InputInterface  $input,
        OutputInterface $output,
    ): int
    {
        $usersNumber = $input->getOption('usersNumber') ?? 10;
        $articlesNumber = $input->getOption('articlesNumber') ?? 20;
        $commentsNumber = $input->getOption('commentsNumber') ?? 3;
        $users = [];

        for ($i = 0; $i < $usersNumber; $i++) {
            $user = $this->createFakeUser();
            $users[] = $user;
            $output->writeln('User created: ' . $user->getEmail());
        }

        foreach ($users as $user) {
            for ($i = 0; $i < $articlesNumber; $i++) {
                $article = $this->createFakeArticle($user);
                $articles[] = $article;
                $output->writeln('Article created: ' . $article->getTitle());
            }
        }

        foreach ($articles as $article) {
            for ($i = 0; $i < $commentsNumber; $i++) {
                /** @var Article $article */
                $comment = $this->createFakeComment($article);
                $output->writeln('Comment created: ' . $comment->getText());
            }
        }

        return Command::SUCCESS;
    }

    private function createFakeUser(): User
    {
        $user =
            new User(
                $this->faker->firstName,
                $this->faker->lastName,
                $this->faker->email,
                $this->faker->password,
            );

        $this->createUserCommand->handle($user);
        return $this->usersRepository->getUserByEmail($user->getEmail());
    }

    private function createFakeArticle(User $author): Article
    {
        $article = new Article(
            $author->getId(),
            $this->faker->sentence(6, true),
            $this->faker->realText
        );

        $this->articleCommand->handle($article);
        return $this->articlesRepository->findByTitle($article->getTitle());
    }

    private function createFakeComment(Article $article): Comment
    {
        $comment = new Comment(
            $article->getId(),
            $this->faker->numberBetween(1, $this->usersRepository->getCount()),
            $this->faker->realText
        );

        $this->commentCommand->handle($comment);
        return $comment;
    }

}
