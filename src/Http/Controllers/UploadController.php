<?php

namespace Ek0519\Quilljs\Http\Controllers;


use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;

class UploadController extends Controller
{
    /**
     * Store an attachment for a Trix field.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NovaRequest $request)
    {
        $novaDependencies = $request->newResource()->availableFields($request)->whereInstanceOf(Epartment\NovaDependencyContainer\NovaDependencyContainer::class);

        if (count($novaDependencies) > 0) {
            foreach ($novaDependencies as $dependency) {
                foreach ($dependency->meta['fields'] as $dependencyField) {
                    if (isset($dependencyField->attribute) && $dependencyField->attribute == $request->field) {
                        $field = $dependencyField;
                    }
                }
            }
        } else {
            $field = $request->newResource()
                             ->availableFields($request)
                             ->findFieldByAttribute($request->field, function () {
                                 abort(404);
                             });
        }

        if (! isset($field)) {
            abort(404);
        }


        return response()->json(['url' => call_user_func(
            $field->attachCallback, $request
        ),'all' => $request]);
    }

    /**
     * Delete a single, persisted attachment for a Trix field by URL.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function destroyAttachment(NovaRequest $request)
    {
        $field = $request->newResource()
                        ->availableFields($request)
                        ->findFieldByAttribute($request->field, function () {
                            abort(404);
                        });

        call_user_func(
            $field->detachCallback, $request
        );
    }

    /**
     * Purge all pending attachments for a Trix field.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function destroyPending(NovaRequest $request)
    {
        $field = $request->newResource()
                        ->availableFields($request)
                        ->findFieldByAttribute($request->field, function () {
                            abort(404);
                        });

        call_user_func(
            $field->discardCallback, $request
        );
    }
}
