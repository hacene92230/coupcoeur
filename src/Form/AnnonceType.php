<?php

namespace App\Form;

use App\Entity\Bien;
use App\Entity\Images;
use App\Entity\Annonce;
use App\Repository\BienRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("bien", EntityType::class, [
                "label" => "Pour quel bien souhaitez-vous créer cette annonce",
                'class' => Bien::class,
                'query_builder' => function (BienRepository $bien) {
                    return $bien->createQueryBuilder('b')
                        ->andWhere('b.disponible = :val')
                        ->setParameter('val', true)
                        ->orderBy('b.nom', 'ASC');
                },
                'choice_label' => "nom"
            ])

            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'Location' => "location",
                    'Vente' => "vente",
                ],
            ]) 
            ->add('images', FileType::class, [
                'label' => "Selectionner vos photos",

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // Add multiple
                'multiple' => true,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                /*
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            "image/png",
                            "image/jpeg",
                            "image/jpg",
                            "image/gif",
                            "image/x-citrix-jpeg",
                            "image/x-citrix-png",
                            "image/x-png",
                        ],
                        'mimeTypesMessage' => "Ce type de fichier n'est pas autorisé",
                    ])
                    
                ],*/
            ])
            ->add('titre', TextType::class, ["label" => "Saisir le titre de l'annonce que vous souhaitez mettre en ligne", "attr" => ["placeholder" => "Titre de l'annonce"]])
            ->add('texte', TextareaType::class, ["label" => "Saisir le contenu de l'annonce qui sera associé au bien en question", "attr" => ["placeholder" => "Contenu de l'annonce"]]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
