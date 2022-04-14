<?php

namespace App\Commands\SymfonyCommands;

use App\Commands\DeleteArticleCommand;
use App\Repositories\ArticleRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class DeleteArticle extends Command
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        private DeleteArticleCommand $articleCommand
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('article:delete')
            ->setDescription('Delete a article')
            ->addArgument(
                'id',
                InputArgument::REQUIRED,
                'Id of a article to delete'
            )
            ->addOption(
                'need-question',
                'nnq',
                InputOption::VALUE_NONE,
                'Do I need to ask before deleting',
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output,): int {
        if ($input->getOption('need-question')) {
            $question = new ConfirmationQuestion(
                'Delete article [Y/n]? ',
                false
            );

            if (!$this->getHelper('question')->ask($input, $output, $question))
            {
                return Command::SUCCESS;
            }
        }

        $article = $this->articleRepository->findById($input->getArgument('id'));
        $this->articleCommand->handle($article);

        $output->writeln(sprintf("Article %s deleted", $article->getId()));
        return Command::SUCCESS;
    }

}