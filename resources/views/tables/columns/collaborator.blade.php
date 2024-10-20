@php
    $data = $getState();
    $collaborators = \App\Models\Collaborator::whereIn('id',\App\Models\CollaboratorProject::query()->where('project_id',\App\Models\Task::find($data)->project_id)->pluck('collaborator_id')->toArray())->get();
@endphp
<div>
    <select name="collaborator_id" id="collaborator_id" class="w-full">
        @foreach($collaborators as $collaborator)
            <option value="{{ $collaborator->id }}">{{ $collaborator->name }}</option>
        @endforeach
    </select>
</div>
