<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            1 => [
                'name' => 'Huawei P Smart 2021',
                'description' => "Annoncé le 14 octobre 2020, le Huawei P smart 2021 est un modèle entrée de gamme qui est équipé d'un SoC Kirin 710A, épaulé par 4 Go de RAM et 128 Go de stockage, extensible via microSD. A l'arrière se trouve un bloc de 4 modules photos : 48 mégapixels, 8 mégapixels (ultra grand-angle), et 2 modules de 2 mégapixels chacun (macro et portrait). Il possède une batterie de 5000 mAh compatible charge rapide (22.5 W). Ce modèle ne bénéficie pas des services Google.",
                'price' => 272,
            ],
            2 => [
                'name' => 'iPhone 12',
                'description' => "L'iPhone 12 est le modèle principal de la 14e génération de smartphone d'Apple annoncé le 13 octobre 2020. Il est équipé d'un écran de 6,1 pouces OLED HDR 60 Hz, d'un double capteur photo avec ultra grand-angle et d'un SoC Apple A14 Bionic compatible 5G (sub-6 GHz).",
                'price' => 740,
            ],
            3 => [
                'name' => 'Samsung Galaxy S21 Ultra',
                'description' => "Le Galaxy S21 Ultra inaugure l'année 2021 du géant Samsung et il veut frapper fort. Certes, il ne propose pas de formule abracadabrantesque pour surprendre son public, mais il sait frapper là où il faut et comme il faut pour toucher son public. Voici notre test complet de ce smartphone premium.
                ",
                'price' => 999,
            ],
            4 => [
                'name' => 'Sony Xperia 5 III',
                'description' => "Le Sony Xperia 5 III est le petit frère du Xperia 1 III. Leurs différences se situent au niveau de la résolution d'écran (ici on trouvera du FHD+ et de la 4K sur le fleuron) et une mémoire vive et un stockage revu à la baisse (8 Go de RAM et 128 Go de stockage).",
                'price' => 976,
            ],
            5 => [
                'name' => 'LG G6',
                'description' => "Le LG G6 est le smartphone haut de gamme du constructeur coréen pour l'année 2017. Il présente très peu de bordures et un écran au ratio atypique de 18:9. La qualité du son et de l'appareil sont la priorité de LG qui veut renouer avec le succès grâce à son G6.",
                'price' => 249,
            ],
            6 => [
                'name' => 'Xiaomi Redmi Note 10 Pro',
                'description' => "Le Xiaomi Redmi Note 10 Pro est un smartphone 4G de la famille « Redmi Note 10 » annoncé en mars 2021. Tourné autour de la photographie, il est équipé d'un capteur principal de 108 mégapixels épaulé par 3 capteurs secondaires de 8+5+2 mégapixels. Il est équipé d'un SoC Qualcomm Snapdragon 732G, d'une batterie de 5020 mAh et d'un écran Super AMOLED Full HD+ 120 Hz.",
                'price' => 244,
            ],
            7 => [
                'name' => 'Samsung Galaxy S21 FE',
                'description' => "Le Samsung Galaxy S21 FE est une nouvelle itération de la gamme Samsung Galaxy S21 annoncé en aout 2021. Il est équipé d'un SoC Qualcomm Snapdragon 888 épaulé par 6 à 8 Go de RAM. Il dispose d'un triple capteur photo (12+8+12 mégapixels) polyvalent et d'une batterie de 4500 mAh.",
                'price' => 589,
            ],
            8 => [
                'name' => 'Honor 50',
                'description' => "Annoncé en 2021, le Honor 50 est la version de base du triplé de la marque. Il se diffère de la version pro par un écran plus petit (6,57 pouces), une seule caméra selfie à l'avant et la compatibilité de la charge rapide (66 W au lieu de 100 W).",
                'price' => 460,
            ],
            9 => [
                'name' => 'Wiko Power U10',
                'description' => "Le Wiko Power U10 est un entrée de gamme équipé d'un SoC Mediatek couplé à 3 Go de RAM et 32 Go de stockage. Sa batterie de 5000 mAh est capable de durer 3.5 jours selon la marque, et il est compatible charge rapide (15 W).",
                'price' => 149,
            ],
            10 => [
                'name' => 'Nokia 8000 4G',
                'description' => "Annoncé en Novembre 2020, le Nokia 8000 4G est un smartphone classique (avec clavier) compatible 4G, muni d'une batterie amovible de 1500 mAh, d'une mémoire de 4 Go extensible jusqu'à 32 Go. Il embarque le système d'exploitation léger KaiOS compatible avec WhatsApp, Facebook, Google Assistant, Google Maps et YouTube.",
                'price' => 99,
            ],

        ];

        foreach ($products as $key => $value) {
            $product = new Product();

            $product->setName($value['name']);
            $product->setDescription($value['description']);
            $product->setPrice($value['price']);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
