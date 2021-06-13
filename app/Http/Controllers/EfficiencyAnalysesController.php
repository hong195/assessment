<?php

namespace App\Http\Controllers;

use Doctrine\ORM\EntityManagerInterface;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysisRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\Serializer\Serializer;

class EfficiencyAnalysesController extends Controller
{
    private EfficiencyAnalysisRepository $repository;
    private EntityManagerInterface $em;
    private Serializer $serializer;

    public function __construct(EfficiencyAnalysisRepository $repository, EntityManagerInterface $em,
                                Serializer $serializer)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->serializer = $serializer;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        return JsonResponse::fromJsonString($this->serializer->serialize($this->repository->all(), 'json'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
