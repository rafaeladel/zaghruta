<?php

namespace Zgh\MsgBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Zgh\FEBundle\Repository\UserRepository;

/**
 * Message form type for starting a new conversation
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class NewThreadMessageFormType extends AbstractType
{
    protected $security_context;

    function __construct(SecurityContextInterface $securityContextInterface)
    {
        $this->security_context = $securityContextInterface;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //temporary solution
            ->add('subject', 'hidden', ["attr" =>
                ["value" => "default_value"]
            ])
            ->add("recipient", "thrace_select2_entity", [
                    "class" => "Zgh\FEBundle\Entity\User",
                    "property" => "fullname",
                    'query_builder' => function(UserRepository $er) {
                            return $er->getOtherUsers($this->security_context->getToken()->getUser());
                        },
                    "empty_value" => "To:",
                    "configs" => [
                        "width" => "100%",
                        "minimumInputLength" => "3"
                    ]
                ])

            ->add('body', 'textarea');


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            // intention is set to reply... just to pass csrf test
            'intention'  => 'reply'
        ));
    }

    public function getName()
    {
        return 'fos_message_new_thread';
    }
}
