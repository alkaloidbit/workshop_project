<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Situation;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\AbstractUnicodeString;
use Symfony\Component\String\Slugger\SluggerInterface;
use function Symfony\Component\String\u;

final class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly SluggerInterface $slugger
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadTags($manager);
        $this->loadPosts($manager);
        $this->loadSituations($manager);
        $this->loadAnswers($manager);
    }

    private function loadSituations(ObjectManager $manager): void
    {
        foreach ($this->getSituationData() as $key => $situation) {
            $situation = new Situation();
            $situation->setQuestion($situation[0]);
            $situation->setExplanation($situation[1]);
            $situation->setImageName($situation[2]);

            $manager->persist($situation);
            $this->addReference('situation' . '_' . $key, $situation);
        }

        $manager->flush();
    }

    private function loadAnswers(ObjectManager $manager): void
    {
        foreach ($this->getAnswerData() as $key => $answers) {
            foreach ($answers as $answer) {
                $answer = new Answer();
                $answer->setContent($answer[0]);
                $answer->setValid($answer[1]);
                $answer->setSituation($this->getReference('situation_' . $key));

                $manager->persist($answer);
            }
        }
        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager): void
    {
        foreach ($this->getUserData() as [$fullname, $username, $password, $email, $roles]) {
            $user = new User();
            $user->setFullName($fullname);
            $user->setUsername($username);
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);

            $manager->persist($user);
            $this->addReference($username, $user);
        }

        $manager->flush();
    }

    private function loadTags(ObjectManager $manager): void
    {
        foreach ($this->getTagData() as $name) {
            $tag = new Tag($name);

            $manager->persist($tag);
            $this->addReference('tag-' . $name, $tag);
        }

        $manager->flush();
    }

    private function loadPosts(ObjectManager $manager): void
    {
        foreach ($this->getPostData() as [$title, $slug, $summary, $content, $publishedAt, $author, $tags]) {
            $post = new Post();
            $post->setTitle($title);
            $post->setSlug($slug);
            $post->setSummary($summary);
            $post->setContent($content);
            $post->setPublishedAt($publishedAt);
            $post->setAuthor($author);
            $post->addTag(...$tags);

            foreach (range(1, 5) as $i) {
                /** @var User $commentAuthor */
                $commentAuthor = $this->getReference('john_user');

                $comment = new Comment();
                $comment->setAuthor($commentAuthor);
                $comment->setContent($this->getRandomText(random_int(255, 512)));
                $comment->setPublishedAt(new \DateTime('now + ' . $i . 'seconds'));

                $post->addComment($comment);
            }

            $manager->persist($post);
        }

        $manager->flush();
    }

    /**
     * @return array<array{string, string, string, string, array<string>}>
     */
    private function getUserData(): array
    {
        return [
            // $userData = [$fullname, $username, $password, $email, $roles];
            ['Jane Doe', 'jane_admin', 'kitten', 'jane_admin@symfony.com', [User::ROLE_ADMIN]],
            ['Tom Doe', 'tom_admin', 'kitten', 'tom_admin@symfony.com', [User::ROLE_ADMIN]],
            ['John Doe', 'john_user', 'kitten', 'john_user@symfony.com', [User::ROLE_USER]],
        ];
    }
    /**
     * @return array<array{string,string,string}>
     */
    private function getSituationData(): array
    {
        return [
            ["Quelle est la définition du harcèlement sexuel au travail ?", "Le harcèlement sexuel au travail est défini comme toute forme de comportement à connotation sexuelle portant atteinte à la dignité d'une personne. Selon la loi, il peut inclure des avances non désirées, des commentaires offensants, des demandes sexuelles, ou d'autres comportements similaires.", "image1-652bd8d709caa937728073.png"],
            ["Que devrait faire une personne victime de harcèlement sexuel au travail ?", "La personne victime de harcèlement sexuel devrait signaler les faits à son supérieur hiérarchique, aux ressources humaines ou à toute personne de confiance. Il est important de prendre des mesures immédiates pour résoudre la situation et éviter qu'elle ne persiste.", "image5-652bd9948f77f860920210.jpg"],
            ["Quelle est la responsabilité de l'employeur face au harcèlement sexuel au travail ?", "L'employeur a la responsabilité de prendre des mesures préventives, de former le personnel et de traiter les plaintes de manière sérieuse et confidentielle. Cela peut inclure la mise en place de politiques anti-harcèlement, des sessions de sensibilisation et la création d'un environnement où les employés se sentent en sécurité pour signaler tout incident.", "image7-652bd9f1425fa711874062.jpg"],
            ["Martine demande à son collègue Jules d'appuyer sur le bouton de l'ascenseur dont il bloque le passage afin de se rendre au troisième étage. Jules lui répond: 'Tu sais que pour toi je ferais tout'. C'est la troisième fois aujourd'hui que Jules lui fait cette remarque (la première pour une agrafeuse, la seconde pour un dossier) A présent Martine est gênée lorsqu'elle doit s'adresser à Jules. Mais elle se dit qu'il ne fait rien de mal au fond et que c'est plutôt elle qui devrait se détendre un peu. Martine a-t-elle raison ?", "Ce que fait Jules s'appelle du harcèlement sexuel et est condamnable par la loi. Jules devrait cesser sur le champs ce genre d'agissement même si son intention est de complimenter Martine ou de faire de l'humour. Martine pourrait en référer à son responsable et Jules devrait-être sanctionné.", "image77-652bdb1ce989b410530076.jpg"],
        ];
    }
    /**
     * @return array<array{string,bool}>
     */
    private function getAnswerData(): array
    {
        return  [
            [
                ["A Toute forme de comportement verbal, non verbal ou physique à connotation sexuelle ayant pour objet ou pour effet de porter atteinte à la dignité d'une personne.", 1],
                ["B Un compliment occasionnel sur l'apparence physique d'un collègue.", 0],
            ],
            [
                ["A Se taire et ignorer les comportements pour éviter des problèmes.", 0],
                ["B Signaler le harcèlement à son supérieur hiérarchique, aux ressources humaines ou à toute personne de confiance.", 1],
            ],
            [
                ["A Ignorer les plaintes des employés pour éviter des complications.", 0],
                ["B Prendre des mesures préventives, former le personnel et traiter les plaintes de manière sérieuse et confidentielle.", 1],
            ],
            [
                ["A Oui. Les enjeux du monde professionnel exercent parfois une forme de pression sur les employés. Il est normal que ceux-ci adoptent un comportement un peu plus léger parfois, comme Jules, sans que cela soit condamnable pour autant.", 0],
                ["B Non. Elle a tort. Martine devrait en référer à son responsable et demander à Jules de cesser ses remarques.", 1],
            ],
        ];
    }

    /**
     * @return string[]
     */
    private function getTagData(): array
    {
        return [
            'lorem',
            'ipsum',
            'consectetur',
            'adipiscing',
            'incididunt',
            'labore',
            'voluptate',
            'dolore',
            'pariatur',
        ];
    }

    /**
     * @throws \Exception
     *
     * @return array<int,array<int,mixed>>*/
    private function getPostData(): array
    {
        $posts = [];
        foreach ($this->getPhrases() as $i => $title) {
            // $postData = [$title, $slug, $summary, $content, $publishedAt, $author, $tags, $comments];

            /** @var User $user */
            $user = $this->getReference(['jane_admin', 'tom_admin'][0 === $i ? 0 : random_int(0, 1)]);

            $posts[] = [
                $title,
                $this->slugger->slug($title)->lower(),
                $this->getRandomText(),
                $this->getPostContent(),
                (new \DateTime('now - ' . $i . 'days'))->setTime(random_int(8, 17), random_int(7, 49), random_int(0, 59)),
                // Ensure that the first post is written by Jane Doe to simplify tests
                $user,
                $this->getRandomTags(),
            ];
        }

        return $posts;
    }

    /**
     * @return string[]
     */
    private function getPhrases(): array
    {
        return [
            'Lorem ipsum dolor sit amet consectetur adipiscing elit',
            'Pellentesque vitae velit ex',
            'Mauris dapibus risus quis suscipit vulputate',
            'Eros diam egestas libero eu vulputate risus',
            'In hac habitasse platea dictumst',
            'Morbi tempus commodo mattis',
            'Ut suscipit posuere justo at vulputate',
            'Ut eleifend mauris et risus ultrices egestas',
            'Aliquam sodales odio id eleifend tristique',
            'Urna nisl sollicitudin id varius orci quam id turpis',
            'Nulla porta lobortis ligula vel egestas',
            'Curabitur aliquam euismod dolor non ornare',
            'Sed varius a risus eget aliquam',
            'Nunc viverra elit ac laoreet suscipit',
            'Pellentesque et sapien pulvinar consectetur',
            'Ubi est barbatus nix',
            'Abnobas sunt hilotaes de placidus vita',
            'Ubi est audax amicitia',
            'Eposs sunt solems de superbus fortis',
            'Vae humani generis',
            'Diatrias tolerare tanquam noster caesium',
            'Teres talis saepe tractare de camerarius flavum sensorem',
            'Silva de secundus galatae demitto quadra',
            'Sunt accentores vitare salvus flavum parses',
            'Potus sensim ad ferox abnoba',
            'Sunt seculaes transferre talis camerarius fluctuies',
            'Era brevis ratione est',
            'Sunt torquises imitari velox mirabilis medicinaes',
            'Mineralis persuadere omnes finises desiderium',
            'Bassus fatalis classiss virtualiter transferre de flavum',
        ];
    }

    private function getRandomText(int $maxLength = 255): string
    {
        $phrases = $this->getPhrases();
        shuffle($phrases);

        do {
            $text = u('. ')->join($phrases)->append('.');
            array_pop($phrases);
        } while ($text->length() > $maxLength);

        return $text;
    }

    private function getPostContent(): string
    {
        return <<<'MARKDOWN'
            Lorem ipsum dolor sit amet consectetur adipisicing elit, sed do eiusmod tempor
            incididunt ut labore et **dolore magna aliqua**: Duis aute irure dolor in
            reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
            deserunt mollit anim id est laborum.

              * Ut enim ad minim veniam
              * Quis nostrud exercitation *ullamco laboris*
              * Nisi ut aliquip ex ea commodo consequat

            Praesent id fermentum lorem. Ut est lorem, fringilla at accumsan nec, euismod at
            nunc. Aenean mattis sollicitudin mattis. Nullam pulvinar vestibulum bibendum.
            Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos
            himenaeos. Fusce nulla purus, gravida ac interdum ut, blandit eget ex. Duis a
            luctus dolor.

            Integer auctor massa maximus nulla scelerisque accumsan. *Aliquam ac malesuada*
            ex. Pellentesque tortor magna, vulputate eu vulputate ut, venenatis ac lectus.
            Praesent ut lacinia sem. Mauris a lectus eget felis mollis feugiat. Quisque
            efficitur, mi ut semper pulvinar, urna urna blandit massa, eget tincidunt augue
            nulla vitae est.

            Ut posuere aliquet tincidunt. Aliquam erat volutpat. **Class aptent taciti**
            sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi
            arcu orci, gravida eget aliquam eu, suscipit et ante. Morbi vulputate metus vel
            ipsum finibus, ut dapibus massa feugiat. Vestibulum vel lobortis libero. Sed
            tincidunt tellus et viverra scelerisque. Pellentesque tincidunt cursus felis.
            Sed in egestas erat.

            Aliquam pulvinar interdum massa, vel ullamcorper ante consectetur eu. Vestibulum
            lacinia ac enim vel placerat. Integer pulvinar magna nec dui malesuada, nec
            congue nisl dictum. Donec mollis nisl tortor, at congue erat consequat a. Nam
            tempus elit porta, blandit elit vel, viverra lorem. Sed sit amet tellus
            tincidunt, faucibus nisl in, aliquet libero.
            MARKDOWN;
    }

    /**
     * @throws \Exception
     *
     * @return array<Tag>
     */
    private function getRandomTags(): array
    {
        $tagNames = $this->getTagData();
        shuffle($tagNames);
        $selectedTags = \array_slice($tagNames, 0, random_int(2, 4));

        return array_map(function ($tagName) {
            /** @var Tag $tag */
            $tag = $this->getReference('tag-' . $tagName);

            return $tag;
        }, $selectedTags);
    }
}
