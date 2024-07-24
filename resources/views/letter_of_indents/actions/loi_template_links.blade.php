@foreach($templateTypes as $LOITemplate)
<a href="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id,'type' => $LOITemplate ]) }}">
    {{ ucwords( str_replace('_', ' ', $LOITemplate) )}}
</a>
@endforeach