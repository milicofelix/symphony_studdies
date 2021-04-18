<?php

namespace App\Controller;

use App\Entity\Produto;
use App\Form\ProdutoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ProdutoController extends AbstractController
{
    /**
     * @Route("/produto", name="listar_produto")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $produtos = $em->getRepository(Produto::class)->findAll();

        return $this->render('produto/index.html.twig', [
            'produtos' => $produtos,
        ]);
    }

    /**
     * @param Request $request
     *
     * @Route("/produto/cadastrar", name="cadastrar_produto")
     * @Template("produto/create.html.twig")
     * @return Response
     */
    public function create(Request $request)
    {
        $produto = new Produto();

        $form = $this->createForm(ProdutoType::class, $produto);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->persist($produto);
            $em->flush();

            $this->get('session')->getFlashBag()->set('success', 'Produto foi salvo com sucesso!');
            $this->addFlash('success', "Produto cadastrado!");
            return $this->redirectToRoute('listar_produto');


        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @param Request $request
     * @return Response
     * @Template("produto/update.html.twig")
     * @Route("produto/editar/{id}", name="editar_produto")
     */
    public function update(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $produto = $em->getRepository(Produto::class)->find($id);

        $form = $this->createForm(ProdutoType::class, $produto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($produto);
            $em->flush();

            $this->get("session")->getFlashBag()->set("success", "O Produto " . $produto->getNome() . " foi alterado com sucesso!");
            return $this->redirectToRoute("listar_produto");
        }

        return [
            'produto' => $produto,
            'form' => $form->createView()
        ];
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return array
     * @Route("produto/visualizar/{id}", name="visualizar_produto")
     * @Template("produto/view.html.twig")
     */
    public function view(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $produto = $em->getRepository(Produto::class)->find($id);

        return [
            'produto' => $produto
        ];
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @Route("produto/apagar/{id}", name="apagar_produto")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $produto = $em->getRepository(Produto::class)->find($id);

        if (!$produto) {
            $mensagem = "Produto não foi encontrado!";
            $tipo = "warning";
        } else {
            $em->remove($produto);
            $em->flush();
            $mensagem = "Produto foi excluído com sucesso!";
            $tipo = "success";
        }

        $this->get('session')->getFlashBag()->set($tipo, $mensagem);
        return $this->redirectToRoute("listar_produto");

    }
}
