<?php


namespace App\Controller\Api;


use App\DTO\TaskDTO;
use App\DTOBuilder\TaskDTOBuilder;
use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Services\FilterService;
use App\Services\SerializerService;
use App\Services\TaskService;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Normalizer\ArrayNormalizerInterface;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    private ValidatorInterface $validator;

    public function __construct(
        TaskService $taskService,
        TaskRepository $taskRepository,
        FilterService $filterService,
        SerializerService $serializerService,
        TaskDTOBuilder $taskDTOBuilder,
        ValidatorInterface $validator
    ) {
        $this->taskService = $taskService;
        $this->taskRepository = $taskRepository;
        $this->filterService = $filterService;
        $this->serializerService = $serializerService;
        $this->taskDTOBuilder = $taskDTOBuilder;
        $this->validator = $validator;
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
    public function create(Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_MANAGER');
        $taskDto = $this->serializerService->deserialize($request->getContent(), TaskDTO::class, 'json');

        $errors = $this->validator->validate($taskDto);
        if (count($errors) > 0) {
            $data = [];

            foreach ($errors as $error) {
                $data[] = $error->getMessage();
            }
            return new JsonResponse(
                [
                    'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'message' => 'Check your data to correct.',
                    'errors' => $data
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        $task = $this->serializerService->denormalize($taskDto, Task::class);

        $newTask = (new TaskDTOBuilder())->build($this->taskService->create(
            $task,
            $this->getUser()
        ));

        $jsonContent = $this->serializerService->normalize($newTask,'json');

        return new JsonResponse(
            $jsonContent,
            Response::HTTP_OK
        );
    }

    /**
     * @FOSRest\Post("/{id}/edit")
     */
    public function edit(Request $request, int $id): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ALLOW_EDIT');
        if (!$task = $this->taskRepository->findOneBy(['id' => $id])) {
            return new JsonResponse(
                [
                    'code' => Response::HTTP_NOT_FOUND,
                    'message' => 'Task with id ' . $id . ' not found. Check the correct id'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $taskDto = $this->serializerService->deserialize(
            $request->getContent(),
            TaskDTO::class,
            'json',
        );

        $errors = $this->validator->validate($taskDto);
        if (count($errors) > 0) {
            $data = [];

            foreach ($errors as $error) {
                $data[] = $error->getMessage();
            }
            return new JsonResponse(
                [
                    'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'message' => 'Check your data to correct.',
                    'errors' => $data
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $editTask = (new TaskDTOBuilder())->build($this->taskService->edit(
                $task,
                $taskDto
            ));
        } catch (Exception $e) {
            return new JsonResponse(
                [
                    'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'message' => $e->getMessage() ?? 'Check your data to correct',
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $jsonContent = $this->serializerService->normalize($editTask,'json');

        return new JsonResponse(
            $jsonContent,
            Response::HTTP_OK
        );
    }

    /**
     * @FOSRest\Patch("/{id}/delete")
     */
    public function delete(int $id): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_MANAGER');
        if (!$task = $this->taskRepository->findOneBy(['id' => $id])) {
            return new JsonResponse(
                [
                    'code' => Response::HTTP_NOT_FOUND,
                    'message' => 'Task with id ' . $id . ' not found. Check the correct id'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        try {
            $this->taskService->delete($task);
        } catch (\TaskDoneException $e) {
            return new JsonResponse(
                [
                    'code' => Response::HTTP_METHOD_NOT_ALLOWED,
                    'message' => 'This task have not completed child tasks. You must complete child task before removal'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        return new JsonResponse(
            [
                'code' => Response::HTTP_OK,
                'message' => 'Task with id ' . $id . ' was deleted',
            ],
            Response::HTTP_OK
        );
    }
}