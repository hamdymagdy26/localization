<?php

namespace App\Http\Requests;

use App\Http\Requests\Abstracts\AbstractFormRequest;
use Dev\Application\Utility\InternalExecutiveRole;
use Dev\Application\Utility\ParticipantNationality;
use Dev\Application\Utility\ProjectType;
use Dev\Application\Utility\StudentType;
use Dev\Infrastructure\Models\ProjectTranslation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Class ProjectFormRequest
 * @package App\Http\Requests
 */
class ProjectFormRequest extends AbstractFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * @param Request $request
     * @return array
     */
    public function rules(Request $request)
    {
        $method = $request->getMethod();
        $action = $request->route()->getActionMethod();

        if ($method === 'POST') {
            if ($action === 'store') {
                return [
                    'management_id' => 'required|integer|exists:management,id',
                    'faculty_id' => 'required|integer',
                    'project_type' => ['required', Rule::in(ProjectType::types())],
                    'internal_executive_role' => ['sometimes', Rule::in(InternalExecutiveRole::roles())],
                    'nationality_id' => ['required', Rule::in(ParticipantNationality::nationalities())],
                    'external_executive_role' => 'sometimes|string',
                    'participants_number' => 'required|integer',
                    'participant_information' => 'required|array',
                    'participant_information.*.student_type' => ['required_with:participant_information', Rule::in(StudentType::genders())],
                    'participant_information.*.from' => 'required_with:participant_information|integer',
                    'participant_information.*.to' => 'required_with:participant_information|integer',
                    'translation' => 'bail|array|required',
                    'translation.*.name' => [
                        'required_with:translation',
                        'distinct',
                        function ($attribute, $value, $fail) {
                            $index = filter_var($attribute, FILTER_SANITIZE_NUMBER_INT);
                            $existedTranslation = ProjectTranslation::where(
                                [
                                    ['name', $this->translation[$index]['name']],
                                    ['locale', $this->translation[$index]['locale']]
                                ]
                            )->first();
                            if (!is_null($existedTranslation)) {
                                $fail('The ' . $attribute . ' has already been taken.');
                            }
                        }
                    ],
                    'translation.*.description' => ['sometimes', 'string'],
                    'translation.*.locale' => 'required_with:translation|exists:localizations,code|distinct'
                ];
            }
        } else if ($method === 'PUT') {
            if ($action === 'update') {
                return [
                    'faculty_id' => 'sometimes|integer',
                    'project_type' => ['sometimes', Rule::in(ProjectType::types())],
                    'internal_executive_role' => ['sometimes', Rule::in(InternalExecutiveRole::roles())],
                    'nationality_id' => ['sometimes', Rule::in(ParticipantNationality::nationalities())],
                    'external_executive_role' => 'sometimes|string',
                    'participants_number' => 'sometimes|integer',
                    'participants_number' => 'sometimes|integer',
                    'participant_information.*.student_type' => ['required_with:participant_information', Rule::in(StudentType::genders())],
                    'participant_information.*.from' => 'required_with:participant_information|integer',
                    'participant_information.*.to' => 'required_with:participant_information|integer',
                    'translation' => 'bail|array|sometimes',
                    'translation.*.name' => [
                        'required_with:translation',
                        'distinct',
                        function ($attribute, $value, $fail) {
                            $index = filter_var($attribute, FILTER_SANITIZE_NUMBER_INT);
                            $existedTranslation = ProjectTranslation::where(
                                [
                                    ['name', $this->translation[$index]['name']],
                                    ['locale', $this->translation[$index]['locale']],
                                    ['project_id', '!=', $this->project->id]
                                ]
                            )->first();
                            if (!is_null($existedTranslation)) {
                                $fail('The ' . $attribute . ' has already been taken.');
                            }
                        }
                    ],
                    'translation.*.description' => ['sometimes', 'string'],
                    'translation.*.locale' => 'required_with:translation|exists:localizations,code|distinct'
                ];
            }
        }

        if ($method === 'GET') {
            if ($action === 'index') {
                return [
                    'limit' => 'sometimes|integer',
                ];
            }
        }

        if ($method == 'DELETE') {
            if ($action == 'deleteTranslation') {
                return [
                    'locale' => 'required|exists:localizations,code',
                ];
            }
        }
        return [];
    }
}
