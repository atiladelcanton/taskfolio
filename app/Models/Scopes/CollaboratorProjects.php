<?php

namespace App\Models\Scopes;

use App\Models\Collaborator;
use App\Models\CollaboratorProject;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CollaboratorProjects implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
       if(auth()->user()->isCollaborator()){
           $collaboratorId = \DB::table('collaborators')
               ->where('user_id', auth()->id())->first()->id;
           $projects = CollaboratorProject::query()
               ->where('collaborator_id', $collaboratorId)
               ->pluck('project_id')->toArray();

           $builder->whereIn('project_id', $projects);
       }
    }
}
