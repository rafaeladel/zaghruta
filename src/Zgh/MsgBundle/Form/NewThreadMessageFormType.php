<?php

namespace Zgh\MsgBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Message form type for starting a new conversation
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class NewThreadMessageFormType extends AbstractType
{
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
                    "empty_value" => "To:",
                    "configs" => [
                        "width" => "100%",
                    ]
                ])
            ->add('body', 'textarea');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'intention'  => 'message',
        ));
    }

    public function getName()
    {
        return 'fos_message_new_thread';
    }
}
