<div class="container mx-auto py-8">
    <div class="grid lg:grid-cols-3">
        <div class="mb-4 mx-4 ">
            <h1 class="mb-4 text-blue-500 text-3xl font-bold"> {{str($model->name)->human()->title()}} </h1>
            @@if($errors->any())
            <ul class="list-disc list-inside text-sm text-red-500">
                @@foreach ($errors->all() as $error)
                <li class="">@{{ $error }}</li>
                @@endforeach
            </ul>
            @@endif
        </div>

        <div class="col-span-2 bg-white shadow rounded-lg overflow-hidden">
            <form wire:submit.prevent="save()">
                <div class="space-y-3 p-4">

@@csrf

@foreach($model->relations as $rel)
@if($rel->type === 'BelongsTo')
        <div class="">
            <label class="block text-sm font-semibold text-gray-700" for="{{$rel->local_key}}">{{str($rel->name)->title()}}</label>
            <select wire:model="{{$rel->local_key}}" name="{{$rel->local_key}}" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="">Select one</option>
                @@foreach((\{{$rel->model->complete_name}}::all() ?? [] ) as ${{$rel->name}})
                <option value="{{code()->doubleCurlyOpen()}}${{$rel->name}}->id{{code()->doubleCurlyClose()}}"
                >{{code()->doubleCurlyOpen()}}${{$rel->name}}->{{collect($rel->model->table->columns)->filter(function($col,$key) {
                    return $col->type == 'String'; })->map(function($col){ return $col->name;})->first()}}{{code()->doubleCurlyClose()}}</option>
                @@endforeach
            </select>
        </div>
@endif
@endforeach
@foreach($model->table->columns as $column)
@if(!str($column->name)->matches('/id$/') && !str($column->name)->matches('/created_at$/') && !str($column->name)->matches('/updated_at$/') && !str($column->name)->matches('/deleted_at$/'))
        <div class="">
            <label class="block text-sm font-semibold text-gray-700" for="{{$column->name}}">{{str($column->name)->title()}}</label>
@if($column->type=='Text')
            <textarea wire:model="{{$column->name}}" name="{{$column->name}}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded {{$column->type}}"></textarea>
@else
            <input
             wire:model="{{$column->name}}"
            name="{{$column->name}}"
            @if($column->type == 'String')
            type="text"
            maxlength="{{$column->length}}"
            @elseif($column->type == 'DateTime')
            type="date"
            @elseif(str($column->type)->matches('/Int/'))
            type="number"
            @elseif(str($column->type)->matches('/Decimal|Float/'))
            type="number"
            step="0.01"
            @endif
            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded {{$column->type}}"
            @if(!$column->nullable)
            required="required"
            @endif
            >
@endif
            @@if($errors->has('{{$column->name}}'))
            <p class="mt-0.5 text-sm text-red-500">{{code()->doubleCurlyOpen()}}$errors->first('{{$column->name}}'){{code()->doubleCurlyClose()}}</p>
            @@endif
        </div>
@endif
@endforeach
                <div class="bg-gray-100 flex items-center justify-between px-4 py-5 space-x-3">
                    <button type="button" wire:click="showView('list')" class="text-blue-500">Back</button>
                    <button type="submit" class="px-6 py-1.5 border-lg bg-blue-500 text-blue-50 font-semibold rounded hover:bg-blue-700">Save {{str($model->name)->human()->title()}} </button>
                </div>
            </form>
        </div>
    </div>
</div>
