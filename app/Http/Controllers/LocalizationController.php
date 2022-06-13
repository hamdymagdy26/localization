<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocalizationFormRequest;
use App\Http\Resources\LocalizationResource;
use App\Http\Response\Resources\ErrorResponseResource;
use App\Http\Response\Utility\ResponseType;
use Dev\Application\Utility\UploadPath;
use Dev\Domain\Service\LocalizationService;
use Dev\Infrastructure\Models\Localization;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LocalizationController extends Controller
{
    /**
     * @var LocalizationService $localizationService
     */
    private $localizationService;

    /**
     * LocalizationController constructor.
     * @param LocalizationService $localizationService
     */
    public function __construct(LocalizationService $localizationService)
    {
        $this->localizationService = $localizationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  LocalizationFormRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(LocalizationFormRequest $request)
    {
        $filters = $request->validated();
        $localizations = $this->localizationService->index($filters);
        return LocalizationResource::collection($localizations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  LocalizationFormRequest $request
     * @return LocalizationResource|ErrorResponseResource
     */
    public function store(LocalizationFormRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('flag')) {
            $path = $request->file('flag')->store(UploadPath::LOCALIZATION);
            $data['flag'] = $path;
        }
        $data['title'] = trim(preg_replace('/\s+/', ' ', $data['title']));
        $validator = Validator::make($data, [
            'title' => 'unique:localizations'
        ]);
        if ($validator->fails()) {
            return new ErrorResponseResource($validator->getMessageBag());
        }
        if (isset($data['active']) && !boolval($data['active'])) {
            $validator = Validator::make(
                $data,
                [
                    'active' => 'accepted'
                ],
                [
                    'active.accepted' => 'The :attribute must be true when set as default'
                ]
            );
            if ($validator->fails()) {
                return new ErrorResponseResource($validator->getMessageBag());
            }
        }
        $localization = $this->localizationService->create($data);
        return (new LocalizationResource($localization))
            ->additional(ResponseType::simpleResponse('Localization has been created', true));
    }

    /**
     * Display the specified resource.
     *
     * @param  Localization $localization
     * @return LocalizationResource
     */
    public function show(Localization $localization)
    {
        $localization = $this->localizationService->show($localization->id);
        return new LocalizationResource($localization);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  LocalizationFormRequest $request
     * @param  Localization $localization
     * @return LocalizationResource|ErrorResponseResource
     */
    public function update(LocalizationFormRequest $request, Localization $localization)
    {
        $data = $request->validated();
        if ($request->hasFile('flag')) {
            Storage::delete($localization->flag);
            $path = $request->file('flag')->store(UploadPath::LOCALIZATION);
            $data['flag'] = $path;
        }
        if (isset($data['title'])) {
            $data['title'] = trim(preg_replace('/\s+/', ' ', $data['title']));
            $validator = Validator::make($data, [
                'title' => Rule::unique('localizations', 'title')->ignore($localization)
            ]);
            if ($validator->fails()) {
                return new ErrorResponseResource($validator->getMessageBag());
            }
        }
        if (isset($data['default']) && boolval($data['default'])) {
            $data['active'] = $request->get('active', $localization->active);
            $validator = Validator::make(
                $data,
                [
                    'active' => 'accepted'
                ],
                [
                    'active.accepted' => 'The :attribute must be true when set as default'
                ]
            );
            if ($validator->fails()) {
                return new ErrorResponseResource($validator->getMessageBag());
            }
        }
        $localization = $this->localizationService->update($localization->id, $data);
        return (new LocalizationResource($localization))->additional(ResponseType::simpleResponse('Localization has been updated', true));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Localization $localization
     * @return LocalizationResource
     */
    public function destroy(Localization $localization)
    {
        $this->localizationService->delete($localization);
        return new LocalizationResource(ResponseType::simpleResponse('Localization has been deleted', true));
    }

//    public function index($locale)
//    {
//        App::setLocale($locale);
//        session()->put('locale', $locale);
//        return redirect()->back();
//    }
}
