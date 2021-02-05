<?php

namespace App\Form;

use App\Entity\Bien;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class BienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("proprietaire", EntityType::class, ["label" => "Propriétaire du bien", 'class' => User::class, 'choice_label' => "username"])
            ->add('nom', TextType::class, ["label" => "Saisir un nom pour pouvoir identifier plus facilement votre bien", "attr" => ["placeholder" => "Saisir un nom"]])
            ->add('chauffage', ChoiceType::class, [
                'choices'  => [
                    'Électrique' => "electrique",
                    'Gaz' => "gaz",
                    'Fioul' => "fioul",
                    "Poêle à bois" => "poele a bois",
                    "Plancher chauffant" => "plancher chauffant",
                ],
            ])
            ->add('surface', IntegerType::class, ["label" => "Saisir la surface du bien en mètre carré", "attr" => ["placeholder" => "Surface", "min" => 10, "max" => 400, "value" => 20]])
            ->add('pieces', IntegerType::class, ["label" => "nombre de pièces du bien", "attr" => ["placeholder" => "Nombre de pièces", "min" => 1, "max" => 15, "value" => 2]])
            ->add('chambres', IntegerType::class, ["label" => "Combien de chambres comporte ce bien", "attr" => ["placeholder" => "Nombre de chambres", "min" => 1, "max" => 10, "value" => 1]])
            ->add('etages', IntegerType::class, ["label" => "à quel étage se trouve ce bien, si c'est une maison laissez 0", "attr" => ["placeholder" => "étage ou se trouve le bien", "min" => 0, "max" => 30, "value" => 0]])
            ->add('prix', IntegerType::class, ["label" => "prix du bien", "attr" => ["min" => 0, "max" => 30, "value" => 0]])
            ->add('adresse', TextType::class, ["label" => "Saisir l'adresse du bien", "attr" => ["placeholder" => "Adresse"]])
            ->add('ville', TextType::class, ["label" => "La ville où se situe votre bien", "attr" => ["placeholder" => "Ville"]])
            ->add('code_postal', IntegerType::class, ["label" => "Code postal de votre bien", "attr" => ["placeholder" => "Code postal", "min" => 0, "value" => 1]])
            ->add('disponible', CheckboxType::class, ["label" => "Ce bien est disponible", "data" => true]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bien::class,
        ]);
    }
}
