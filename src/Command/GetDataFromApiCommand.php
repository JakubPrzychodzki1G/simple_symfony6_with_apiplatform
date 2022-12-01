<?php

namespace App\Command;

use App\Entity\PostPosts;
use App\Repository\PostPostsRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:get-data-from-api',
    description: 'Add a short description for your command',
)]
class GetDataFromApiCommand extends Command
{
    public function __construct( private PostPostsRepository $postPosts)
    {
        parent::__construct();
    }
    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $ch = curl_init();
         // set url
        curl_setopt($ch, CURLOPT_URL, "https://jsonplaceholder.typicode.com/posts");
         //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         // $output contains the output string
        $posts=json_decode(curl_exec($ch),true);
        curl_close($ch);

        $ch1 = curl_init();
        curl_setopt($ch1, CURLOPT_URL, "https://jsonplaceholder.typicode.com/users");
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
        $users=json_decode(curl_exec($ch1),true);
        curl_close($ch1);

        foreach($posts as $post){
            $postPost = new PostPosts();
            $postPost->setTitle($post["title"]);
            $postPost->setBody($post["body"]);
            $postPost->setUserId($post["userId"]);
            foreach($users as $user){
                if($user["id"]===$post["userId"]){
                    $postPost->setName($user["name"]);
                    break;
                }
            }
            $this->postPosts->save($postPost,true);
        }
        

        $io->success('Data has been stored inside of database!');

        return Command::SUCCESS;
    }
}
