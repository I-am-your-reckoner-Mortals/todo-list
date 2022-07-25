<?php


namespace App\Controller\Api;


use App\DTOBuilder\TaskDTOBuilder;
use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Services\FilterService;
use App\Services\SerializerService;
use App\Services\TaskService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Normalizer\ArrayNormalizerInterface;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @FOSRest\Route("task")
*/
class TaskController extends AbstractFOSRestController
{
    private TaskService $taskService;
    private TaskRepository $taskRepository;
    private FilterService $filterService;
    private SerializerService $serializerService;
    private TaskDTOBuilder $taskDTOBuilder;

    public function __construct(
        TaskService $taskService,
        TaskRepository $taskRepository,
        FilterService $filterService,
        SerializerService $serializerService,
        TaskDTOBuilder $taskDTOBuilder
    ) {
        $this->taskService = $taskService;
        $this->taskRepository = $taskRepository;
        $this->filterService = $filterService;
        $this->serializerService = $serializerService;
        $this->taskDTOBuilder = $taskDTOBuilder;
    }


    /**
     * @FOSRest\Get("")
     *
     * @FOSRest\QueryParam(name="status", allowBlank=true, description="Filter by status")
     * @FOSRest\QueryParam(name="priority", allowBlank=true, requirements="\d+", description="Filter by priority")
     * @FOSRest\QueryParam(name="createdBy", allowBlank=true, description="Filter by created task user")
     * @FOSRest\QueryParam(name="asignTo", allowBlank=true, description="Filter by asign task user")
     * @FOSRest\QueryParam(name="createdAt", allowBlank=true, description="Page")
     * @FOSRest\QueryParam(name="updatedAt", allowBlank=true, description="Page")
     * @FOSRest\QueryParam(name="completedAt", allowBlank=true, description="Page")
     *
     * @FOSRest\QueryParam(name="orderBy", allowBlank=true, description="Page")
     * @FOSRest\QueryParam(name="orderType", default="ASC", allowBlank=true, description="Page")
     */
    public function index(ParamFetcher $paramFetcher): Response
    {
        $criteria = array_filter($paramFetcher->all(), function ($param) {
            return $param !== 'orderBy' && $param !== 'orderType';
        }, ARRAY_FILTER_USE_KEY);

        $tasks = array_map(function ($task) {
            return $this->taskDTOBuilder->build($task);
        }, $this->filterService->search(
                $this->taskRepository,
                $criteria +
                [
                    'orderBy' => [
                        'order_field' => $paramFetcher->get('orderBy'),
                        'order_type' => $paramFetcher->get('orderType'),
                    ]
                ]
            )
        );

        $jsonContent = $this->serializerService->normalize($tasks,'json');

        return new JsonResponse(
            $jsonContent,
            Response::HTTP_OK
        );
    }

    /**
     * @FOSRest\Post("/create")
    */
    public function create(Request $request)
    {
//        dd(json_decode($request->getContent()));

        dd($this->getUser());
        $this->taskService->create(
            json_decode($request->getContent()),
            $this->getUser()
        );
    }
}