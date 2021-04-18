<?php

namespace App\Controller;


use App\Entity\Produto;
use Doctrine\DBAL\Types\DecimalType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    /**
     * @return Response
     *
     * @Route("hello_world")
     */
    public function world()
    {
        $clube['RS']['portoAlegre'] = "Gremio";
        $clube['RS']['Caxias'] = "Juventude";

        dd($clube);
        return $this->render(
            "hello/mensagem.html.twig", ["mensagem" => "Hello World!", "clubes" => $clube]
        );
    }

    /**
     * @return Response
     *
     * @Route("mostrar-mensagem")
     */
    public function mensagem()
    {
        return $this->render("hello/mensagem.html.twig", [
            'mensagem' => "Olá Symfony!"
        ]);
    }

    /**
     * @return Response
     *
     * @Route("cadastrar-produto")
     *
     */
    public function produto()
    {
        $em = $this->getDoctrine()->getManager();

        $produto = new Produto();
        $produto->setNome("Playstatio 4")
            ->setPreco(2500.00)
            ->setDescricao("Concorrente direto do X-BOX");
        dump($produto);
        $em->persist($produto);
        $em->flush();

        return new Response("O produto " . $produto->getId() . " foi criado!");
    }

    /**
     * @return Response
     *
     * @Route("formulario")
     *
     */
    public function formulario(Request $request)
    {
        $produto = new Produto();

        $form = $this->createFormBuilder($produto)
            ->add('nome', TextType::class, ['required' => true])
            ->add('preco', TextType::class)
            ->add('enviar', SubmitType::class, ['label' => "Salvar"])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            return new Response("Formulario está ok!");
        }

        return $this->render("hello/formulario.html.twig", [
            'form' => $form->createView()
        ]);

    }
}