<?php

namespace App\DataFixtures;


use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Comment;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ArtcleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = \Faker\Factory::create('fr_FR');

        // Creer 3 Categories de Fake

        for ($i=0; $i <=10 ; $i++) {
            $categorie = new Categorie();
            $categorie->setTitre($faker->sentence())
                      ->setResume($faker->text($maxNbChars = 20));
                    
                    $manager->persist($categorie);

        // creation de 4 - 6 Articles

        for ($j=1; $j<= mt_rand(4, 6); $j++)
        { 
            $article = new Article();
            
            $content = '<p>'.join($faker->paragraphs(5),'</p><p>').'</p>';

            $article->setTitle($faker->sentence($nb = 5, $asText = false))
                    ->setContent($content)
                    ->setImage($faker->imageUrl())
                    ->setCreatedAt(new \DateTime())
                    ->setCategorie($categorie);
                $manager->persist($article);

    // Creons des commentaires pour les articles 

    for ($k=1; $k<=mt_rand(4, 10); $k++)
    {
        $comment = New Comment();

        $days = (new \DateTime())->diff ($article->getCreatedAt())->days;
    // 	$minimum = '-'.$days.'days';
        
        $comment->setAuteur($faker->name)
                ->setContenu($faker->realText($maxNbChars = 200, $indexSize = 2))
                ->setCreatedAt($faker->dateTimeBetween('-'. $days. 'days'))
                ->setArticle($article);
        
                $manager-> persist($comment);
    }
  }
}       
        $manager->flush();
    }
}
